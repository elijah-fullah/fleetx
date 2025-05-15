<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;
use App\Models\Station;
use App\Models\OMC;
use Carbon\Carbon;
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsAdvancedTextualRequest;
use App\Mail\PostMail;
use App\Mail\AddDealer;
use App\Jobs\AddDealerJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Infobip\Exception\ApiException;

class DealerController extends Controller
{
    /*public function overviewDealer()
    {
        $allDealer = dealer::select('id', 'first_name', 'middle_name', 'last_name', 'licence_no', 'licence_exp', 'omc_id', 'email', 'phone')->get();
    
        $data = $allDealer->map(function ($item) {
            return [
                'full_name' => trim($item->first_name . " " . ($item->middle_name ?? '') . " " . $item->last_name),
                'licence_exp' => $item->licence_exp,
                'omc_id' => $item->omc_id,
                'email' => $item->email,
                'phone' => $item->phone,
                'licence_no' => $item->licence_no,
            ];
        });
    
        return view('dealers.overviewDealer', compact('allDealer'));
    }*/

    public function addDealers(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'licence_exp' => 'required|date',
            'omc_id' => 'required|array',
            'omc_id.*' => 'exists:omc,id',
            'email' => 'required|email',
            'phone' => 'required',
            'licence_no' => 'required|unique:dealer,licence_no'
        ]);

        $data['licence_exp'] = Carbon::createFromFormat('D M d Y', $data['licence_exp'])->format('Y-m-d');

        $data['device'] = $request->header('User-Agent');
        $data['is_online'] = false;
        $data['last_login'] = null;
        $data['status'] = 'pending';

        $data['userName'] = auth()->user()->name ?? null;
        $data['userId'] = auth()->user()->id ?? null;
        $data['userRole'] = auth()->user()->role ?? null;
        $data['editedUserName'] = null;
        $data['editedUserId'] = null;
        $data['editedUserRole'] = null;
        $data['editedDevice'] = null;
        $data['editedTime'] = null;
        $data['omc_id'] = implode(',', $data['omc_id']);

        DB::insert(
            "INSERT INTO dealer (first_name, middle_name, last_name, licence_exp, omc_id, email, phone, licence_no, device, is_online, last_login, created_at, updated_at, userName, userId, userRole, editedUserName, editedUserId, editedUserRole, editedDevice, editedTime, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['first_name'],
                $data['middle_name'],
                $data['last_name'],
                $data['licence_exp'],
                $data['omc_id'],
                $data['email'],
                $data['phone'],
                $data['licence_no'],
                $data['device'],
                $data['is_online'],
                $data['last_login'],
                $data['userName'],
                $data['userId'],
                $data['userRole'],
                $data['editedUserName'],
                $data['editedUserId'],
                $data['editedUserRole'],
                $data['editedDevice'],
                $data['editedTime'],
                $data['status'],
            ]
        );

        Mail::to($data['email'])->send(new AddDealer($data));

        $configuration = new Configuration(
            host: '1gz3e9.api.infobip.com',
            apiKey: '27e98f12e85460e932de65bd5bc1e517-9206b4e7-d1d0-4cb6-923c-3402d8002772'
        );

        $SmsApi = new SmsApi(config: $configuration);
        $message = new SmsTextualMessage(
            destinations: [new SmsDestination(to: $request->phone)],
            from: 'Fleet XP',
            text: 'Hello ' . $data['first_name'] . ', you have been successfully registered to Fleet XP.'
        );

        $smsRequest = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $SmsApi->sendSmsMessage($smsRequest);
        } catch (ApiException $e) {
            return redirect()->route('addDealer')->with('fail', 'Dealer added but SMS failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Dealer added successfully. Email and SMS sent.');
    }

    public function editDealer($id)
    {
        $dealer = DB::table('dealer')->find($id);
        if (!$dealer) {
            return redirect()->route('overviewDealer')->with('fail', 'Dealer not found.');
        }
        $omcs = DB::table('omc')->select('id', 'omcName')->get();
        return view('dealers.editDealer', compact('dealer', 'omcs'));
    }

    public function updateDealer(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'licence_no' => 'required',
            'licence_exp' => 'required',
        ]);

        if ($request->has('omc_id')) {
            $data['omc_id'] = is_array($request->omc_id) ? implode(',', $request->omc_id) : null;
        }
        
        $data['device'] = $request->header('User-Agent');
        $data['is_online'] = false;
        $data['last_login'] = null;
        DB::table('dealer')
        ->where('id', $id)
        ->update($data);
        return redirect()->route('overviewDealer')->with('success', 'Dealer details updated successfully.');
    }

    public function overviewDealer(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
    
        $allDealer = dealer::select('dealer.id', 'dealer.first_name', 'dealer.middle_name', 'dealer.last_name', 'dealer.licence_no', 'dealer.licence_exp', 'dealer.omc_id', 'dealer.email', 'dealer.phone', 'dealer.status')
        ->selectSub(function ($query) {
            $query->from('omc')
                ->selectRaw('COUNT(*)')
                ->whereRaw('FIND_IN_SET(omc.id, dealer.omc_id)');
        }, 'omc_count')
        ->selectSub(function ($query) {
            $query->from('station')
                ->selectRaw('COUNT(*)')
                ->whereRaw('FIND_IN_SET(dealer.id, station.dealer_id)');
        }, 'station_count')
        ->where(function($query) use ($search) {
            $query->where('dealer.first_name', 'like', '%'.$search.'%')
            ->orWhere('dealer.last_name', 'like', '%'.$search.'%')
            ->orWhere('dealer.email', 'like', '%'.$search.'%')
            ->orWhere('dealer.phone', 'like', '%'.$search.'%')
            ->orWhere('dealer.licence_no', 'like', '%'.$search.'%');
        })

        ->orderBy('dealer.id', 'desc')
        ->paginate($perPage);
        return view('dealers.overviewDealer', compact('allDealer', 'search', 'perPage'));
    }

    public function getDealerStats()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = dealer::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
        
            $previousWeekData[] = dealer::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
        }

        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getDealerAverage()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
            $currentWeekData[] = dealer::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
            $previousWeekData[] = dealer::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
        }
    
        $currentWeekAverage = array_sum($currentWeekData) / 7;
        $previousWeekAverage = array_sum($previousWeekData) / 7;
    
        return response()->json([
            'labels' => $dates, 
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData,
            'currentWeekAverage' => $currentWeekAverage,
            'previousWeekAverage' => $previousWeekAverage
        ]);
    }

    public function getTopDealers()
    {
        try {
            $topDealer = DB::table('dealer')
            ->select(
                'dealer.id',
                DB::raw("TRIM(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(middle_name, ''), ' ', COALESCE(last_name, ''))) as full_name"),
                DB::raw("(
                    SELECT COUNT(*) 
                    FROM station 
                    WHERE FIND_IN_SET(dealer.id, station.dealer_id)
                ) as station_count")
            )
            ->having('station_count', '>', 0)
            ->orderByDesc('station_count')
            ->limit(5)
            ->get();
        
            $result = $topDealer->map(function ($dealer) {
                $stations = DB::table('station')
                ->whereRaw('FIND_IN_SET(?, station.dealer_id)', [$dealer->id])
                ->select(DB::raw("COALESCE(station.stationName, '') as name"))
                ->pluck('name')
                ->toArray();
            
                return [
                    'id' => $dealer->id,
                    'name' => $dealer->full_name,
                    'station_count' => $dealer->station_count,
                    'stations' => $stations,
                ];
            });
        
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error fetching top Dealers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load Dealer data'], 500);
        }
    }

    public function viewDealerProfile($id)
    {
        $dealer = DB::table('dealer')->find($id);
        if (!$dealer) {
            return redirect()->route('viewDealerProfile')->with('fail', 'Dealer not found.');
        }
    
        $omcIds = array_filter(explode(',', $dealer->omc_id));
        $omcCount = DB::table('omc')->whereIn('id', $omcIds)->count();
    
        $stationCount = DB::table('station')
        ->whereRaw("FIND_IN_SET(?, dealer_id)", [$dealer->id])
        ->count();

    
        return view('dealers.viewDealerProfile', [
            'dealer' => $dealer,
            'omcCount' => $omcCount,
            'stationCount' => $stationCount
        ]);
    }

    public function dealerStationOverview($id, Request $request)
    {
        $dealer = DB::table('dealer')->find($id);
        if (!$dealer) {
            return redirect()->back()->with('fail', 'Dealer not found.');
        }
    
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search');
    
        $stations = DB::table('station')
        ->whereRaw('FIND_IN_SET(?, dealer_id)', [$id])
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($subquery) use ($search) {
                $subquery->where('stationName', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    
        return view('dealers.dealerStationOverview', [
            'dealer' => $dealer,
            'stations' => $stations,
            'search' => $search,
            'perPage' => $perPage
        ]);
    }

    public function getDealerStationStats($dealerId)
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();

        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Station::whereRaw('FIND_IN_SET(?, dealer_id)', [$dealerId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = Station::whereRaw('FIND_IN_SET(?, dealer_id)', [$dealerId])
            ->whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))
            ->count();
        }
    
        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getDealerStationAverage($dealerId)
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Station::whereRaw('FIND_IN_SET(?, dealer_id)', [$dealerId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = Station::whereRaw('FIND_IN_SET(?, dealer_id)', [$dealerId])
            ->whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))
            ->count();
        }
    
        $currentWeekAverage = array_sum($currentWeekData) / 7;
        $previousWeekAverage = array_sum($previousWeekData) / 7;
    
        return response()->json([
            'labels' => $dates, 
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData,
            'currentWeekAverage' => $currentWeekAverage,
            'previousWeekAverage' => $previousWeekAverage
        ]);
    }

    public function dealerOMCOverview($id, Request $request)
    {
        $dealer = DB::table('dealer')->find($id);
        if (!$dealer) {
            return redirect()->back()->with('fail', 'Dealer not found.');
        }
    
        $omcId = $dealer->omc_id;
        $omcIds = explode(',', $omcId);

        $perPage = $request->input('per_page', 5);
        $search = $request->input('search');
    
        $omcs = DB::table('omc')
        ->whereIn('id', $omcIds)
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($subquery) use ($search) {
                $subquery->where('omcName', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    
        return view('dealers.dealerOMCOverview', [
            'dealer' => $dealer,
            'omcs' => $omcs,
            'search' => $search,
            'perPage' => $perPage
        ]);
    }
    
    public function getDealerOMCStats($id)
    {
        $dealer = DB::table('dealer')->find($id);
        if (!$dealer) {
            return redirect()->back()->with('fail', 'Dealer not found.');
        }

        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();

        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];

        $omcId = $dealer->omc_id;
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = OMC::whereRaw('FIND_IN_SET(?, id)', [$omcId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = OMC::whereRaw('FIND_IN_SET(?, id)', [$omcId])
            ->whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))
            ->count();
        }
    
        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getDealerOMCAverage($id)
    {

        $dealer = DB::table('dealer')->find($id);
        if (!$dealer) {
            return redirect()->back()->with('fail', 'Dealer not found.');
        }
        
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];

        $omcId = $dealer->omc_id;
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = OMC::whereRaw('FIND_IN_SET(?, id)', [$omcId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = OMC::whereRaw('FIND_IN_SET(?, id)', [$omcId])
            ->whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))
            ->count();
        }
    
        $currentWeekAverage = array_sum($currentWeekData) / 7;
        $previousWeekAverage = array_sum($previousWeekData) / 7;
    
        return response()->json([
            'labels' => $dates, 
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData,
            'currentWeekAverage' => $currentWeekAverage,
            'previousWeekAverage' => $previousWeekAverage
        ]);
    }

    public function suspendDealer($id)
    {
        $dealer = dealer::find($id);
        
        if (!$dealer) {
            return redirect()->back()->with('error', 'Dealer not found.');
        }
        
        if ($dealer->status === 'pending') {
            $dealer->status = 'active';
        } elseif ($dealer->status === 'active') {
            $dealer->status = 'suspended';
        } elseif ($dealer->status === 'suspended') {
            $dealer->status = 'active';
        }
        
        $dealer->save();
        
        return redirect()->route('overviewDealer')->with('success', 'Dealer status updated successfully.');
    }

    public function deleteDealer($id)
    {
        $dealer = dealer::find($id);
        if (!$dealer) {
            return redirect()->back()->with('error', 'Dealer not found.');
        }
        $dealer->delete();
        return redirect()->route('overviewDealer')->with('success', 'Dealer deleted successfully.');
    }

    public function showAddDealerForm()
    {
        $omcs = \DB::table('omc')->get();
    
        return view('dealers.addDealer', compact('omcs'));
    }

}
