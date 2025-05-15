<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Infobip\Configuration;
use App\Models\OMC;
use App\Models\Dealer;
use Carbon\Carbon;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsAdvancedTextualRequest;
use App\Mail\PostMail;
use App\Mail\AddOMC;
use App\Jobs\AddOMCJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Infobip\Exception\ApiException;

class OMCController extends Controller
{
    public function addOMC() { return view('omcs.addOMC'); }

    public function addOMCS(Request $request)
    {
        $data = $request->validate([
            'omcName' => 'required',
            'address' => 'required|min:10',
            'email' => 'required|email|unique:omc,email',
            'phone' => 'required|unique:omc,phone',
            'licence_no' => 'required|unique:omc,licence_no'
        ]);
    
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
    
        DB::insert(
            "INSERT INTO omc (omcName, address, email, phone, licence_no, device, is_online, last_login, created_at, updated_at, userName, userId, userRole, editedUserName, editedUserId, editedUserRole, editedDevice, editedTime, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['omcName'],
                $data['address'],
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
    
            Mail::to($data['email'])->send(new AddOMC($data));
    
            $configuration = new Configuration(
                host: '1gz3e9.api.infobip.com',
                apiKey: '27e98f12e85460e932de65bd5bc1e517-9206b4e7-d1d0-4cb6-923c-3402d8002772'
            );
    
            $SmsApi = new SmsApi(config: $configuration);
            $message = new SmsTextualMessage(
                destinations: [new SmsDestination(to: $request->phone)],
                from: 'Fleet XP',
                text: 'Hello ' . $data['omcName'] . ', you have been successfully registered to Fleet XP.'
            );
    
            $smsRequest = new SmsAdvancedTextualRequest(messages: [$message]);
    
            try {
                $SmsApi->sendSmsMessage($smsRequest);
            } catch (ApiException $e) {
                return redirect()->route('addOMC')->with('fail', 'OMC added but SMS failed: ' . $e->getMessage());
            }
            return redirect()->back()->with('success', 'OMC added successfully. Email and SMS sent.');
    }

    public function editOMC($id)
    {
        $omc = DB::table('omc')->find($id);
        if (!$omc) {
            return redirect()->route('overviewOMC')->with('fail', 'OMC not found.');
        }
        return view('omcs.editOMC', compact('omc'));
    }

    public function updateOMC(Request $request, $id)
    {
        $data = $request->validate([
            'omcName' => 'required',
            'address' => 'required|min:10',
        ]);
    
        $data['device'] = $request->header('User-Agent');
        $data['is_online'] = false;
        $data['last_login'] = null;
        DB::table('omc')
        ->where('id', $id)
        ->update($data);
        return redirect()->route('overviewOMC')->with('success', 'OMC details updated successfully.');
    }

    public function viewProfile($id)
    {
        $omc = DB::table('omc')->find($id);
        if (!$omc) {
            return redirect()->route('viewProfile')->with('fail', 'OMC not found.');
        }
        
        $dealerCount = DB::table('dealer')
        ->whereRaw('FIND_IN_SET(?, omc_id)', [$id])
        ->count();
        
        return view('omcs.viewProfile', [
            'omc' => $omc,
            'dealerCount' => $dealerCount
        ]);
    }

    public function viewDealer($id, Request $request)
    {
        $omc = DB::table('omc')->find($id);
        if (!$omc) {
            return redirect()->back()->with('fail', 'OMC not found.');
        }
    
        $perPage = $request->input('per_page', 5);
        $search = $request->input('search');
    
        $dealers = DB::table('dealer')
        ->whereRaw('FIND_IN_SET(?, omc_id)', [$id])
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($subquery) use ($search) {
                $subquery->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    
        return view('omcs.viewDealer', [
            'omc' => $omc,
            'dealers' => $dealers,
            'search' => $search,
            'perPage' => $perPage
        ]);
    }

    public function overviewOMC(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
    
        $allOMC = OMC::select('omc.id', 'omc.omcName', 'omc.address', 'omc.email', 'omc.phone', 'omc.licence_no', 'omc.status')
        ->selectSub(function($query) {
            $query->from('dealer')
            ->selectRaw('COUNT(*)')
            ->whereRaw('FIND_IN_SET(omc.id, dealer.omc_id)');
        }, 'dealer_count')
        ->where(function($query) use ($search) {
            $query->where('omc.omcName', 'like', '%'.$search.'%')
                ->orWhere('omc.email', 'like', '%'.$search.'%')
                ->orWhere('omc.phone', 'like', '%'.$search.'%')
                ->orWhere('omc.licence_no', 'like', '%'.$search.'%');
        })
        ->orderBy('omc.id', 'desc')
        ->paginate($perPage);
        return view('omcs.overviewOMC', compact('allOMC', 'search', 'perPage'));
    }

    public function getOMCStats()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = OMC::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
        
            $previousWeekData[] = OMC::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
        }

        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }
    
    public function suspendOMC($id)
    {
        $omc = OMC::find($id);
        
        if (!$omc) {
            return redirect()->back()->with('error', 'OMC not found.');
        }
        
        if ($omc->status === 'pending') {
            $omc->status = 'active';
        } elseif ($omc->status === 'active') {
            $omc->status = 'suspended';
        } elseif ($omc->status === 'suspended') {
            $omc->status = 'active';
        }
        
        $omc->save();
        
        return redirect()->route('overviewOMC')->with('success', 'OMC status updated successfully.');
    }

    public function deleteOMC($id)
    {
        $omc = OMC::find($id);
        if (!$omc) {
            return redirect()->back()->with('error', 'OMC not found.');
        }
        $omc->delete();
        return redirect()->route('overviewOMC')->with('success', 'OMC deleted successfully.');
    }

    public function getOMCAverage()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
            $currentWeekData[] = OMC::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
            $previousWeekData[] = OMC::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
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

    public function getTopOMCs()
    {
        try {
            $topOMCs = DB::table('omc')
            ->select(
                'omc.id',
                DB::raw("COALESCE(omc.omcName, '') as name"),
                DB::raw("(
                    SELECT COUNT(*) 
                    FROM dealer 
                    WHERE FIND_IN_SET(omc.id, dealer.omc_id)
                ) as dealer_count")
            )
            ->having('dealer_count', '>', 0)
            ->orderByDesc('dealer_count')
            ->limit(5)
            ->get();
        
            $result = $topOMCs->map(function ($omc) {
                $dealers = DB::table('dealer')
                ->whereRaw('FIND_IN_SET(?, dealer.omc_id)', [$omc->id])
                ->select(DB::raw("TRIM(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(middle_name, ''), ' ', COALESCE(last_name, ''))) as full_name"))
                ->pluck('full_name')
                ->toArray();
            
                return [
                    'id' => $omc->id,
                    'name' => $omc->name,
                    'dealer_count' => $omc->dealer_count,
                    'dealers' => $dealers,
                ];
            });
        
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error fetching top OMCs: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load OMC data'], 500);
        }
    }

    public function getOMCDealerStats($omcId)
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();

        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, omc_id)', [$omcId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, omc_id)', [$omcId])
            ->whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))
            ->count();
        }
    
        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getOMCDealerAverage($omcId)
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, omc_id)', [$omcId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, omc_id)', [$omcId])
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

}