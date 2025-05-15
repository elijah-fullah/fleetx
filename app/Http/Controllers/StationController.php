<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Infobip\Configuration;
use App\Models\Station;
use App\Models\Dealer;
use App\Models\OMC;
use Carbon\Carbon;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsAdvancedTextualRequest;
use App\Mail\PostMail;
use App\Mail\AddStation;
use App\Jobs\AddStationJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Infobip\Exception\ApiException;

class StationController extends Controller
{
    public function overviewStation(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
    
        $allStation = station::select('station.id', 'station.stationName', 'station.district', 'station.chiefdom', 'station.address', 'station.email', 'station.phone', 'station.status')
        ->selectSub(function($query) {
            $query->from('dealer')
            ->selectRaw('COUNT(*)')
            ->whereRaw('FIND_IN_SET(dealer.id, station.dealer_id)');
        }, 'dealer_count')
        ->where(function($query) use ($search) {
            $query->where('station.stationName', 'like', '%'.$search.'%')
                ->orWhere('station.phone', 'like', '%'.$search.'%');
        })
        ->orderBy('station.id', 'desc')
        ->paginate($perPage);
        return view('stations.overviewStation', compact('allStation', 'search', 'perPage'));
    }

    public function addStation() { return view('stations.addStation'); }

    public function addStations(Request $request)
    {
        
        $data = $request->validate([
            'stationName' => 'required',
            'district' => 'required',
            'chiefdom' => 'required',
            'dealer_id' => 'required|array',
            'dealer_id.*' => 'exists:dealer,id',
            'longitude' => 'required',
            'latitude' => 'required',
            'email' => 'required|email|unique:station,email',
            'phone' => 'required|unique:station,phone',
            'address' => 'required'
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
        $data['dealer_id'] = implode(',', $data['dealer_id']);

        DB::insert(
            "INSERT INTO station (stationName, district, chiefdom, dealer_id, longitude, latitude, address, email, phone, device, is_online, last_login, created_at, updated_at, userName, userId, userRole, editedUserName, editedUserId, editedUserRole, editedDevice, editedTime, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['stationName'],
                $data['district'],
                $data['chiefdom'],
                $data['dealer_id'],
                $data['longitude'],
                $data['latitude'],
                $data['address'],
                $data['email'],
                $data['phone'],
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

        Mail::to($data['email'])->send(new AddStation($data));

        $configuration = new Configuration(
            host: '1gz3e9.api.infobip.com',
            apiKey: '27e98f12e85460e932de65bd5bc1e517-9206b4e7-d1d0-4cb6-923c-3402d8002772'
        );

        $SmsApi = new SmsApi(config: $configuration);
        $message = new SmsTextualMessage(
            destinations: [new SmsDestination(to: $request->phone)],
            from: 'Fleet XP',
            text: 'Hello ' . $data['stationName'] . ', you have been successfully registered to Fleet XP.'
        );

        $smsRequest = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $SmsApi->sendSmsMessage($smsRequest);
        } catch (ApiException $e) {
            return redirect()->route('addStation')->with('fail', 'Station added but SMS failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Station added successfully. Email and SMS sent.');
    }

    public function editStation($id)
    {
        $station = DB::table('station')->find($id);
        if (!$station) {
            return redirect()->route('overviewStation')->with('fail', 'Station not found.');
        }
        $dealers = DB::table('dealer')->select('id', 'first_name')->get();
        return view('stations.editStation', compact('station', 'dealers'));
    }

    public function updateStation(Request $request, $id)
    {
        $data = $request->validate([
            'stationName' => 'required',
            'district' => 'nullable',
            'chiefdom' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
        ]);

        if ($request->has('dealer_id')) {
            $data['dealer_id'] = is_array($request->dealer_id) ? implode(',', $request->dealer_id) : null;
        }
        
        $data['device'] = $request->header('User-Agent');
        $data['is_online'] = false;
        $data['last_login'] = null;
        DB::table('station')
        ->where('id', $id)
        ->update($data);
        return redirect()->route('overviewStation')->with('success', 'Station details updated successfully.');
    }

    public function getStationStats()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Station::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
        
            $previousWeekData[] = Station::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
        }

        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getStationAverage()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
            $currentWeekData[] = Station::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
            $previousWeekData[] = Station::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
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

    public function getTopStations()
    {
        try {
            $topStation = DB::table('station')
            ->select(
                'station.id',
                DB::raw("COALESCE(station.stationName, '') as name"),
                DB::raw("(
                    SELECT COUNT(*) 
                    FROM dealer 
                    WHERE FIND_IN_SET(dealer.id, station.dealer_id)
                ) as dealer_count")
            )
            ->having('dealer_count', '>', 0)
            ->orderByDesc('dealer_count')
            ->limit(5)
            ->get();
        
            $result = $topStation->map(function ($station) {
                $dealers = DB::table('dealer')
                ->whereRaw('FIND_IN_SET(?, dealer.id)', [$station->id])
                ->select(DB::raw("TRIM(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(middle_name, ''), ' ', COALESCE(last_name, ''))) as full_name"))
                ->pluck('full_name')
                ->toArray();
            
                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'dealer_count' => $station->dealer_count,
                    'dealers' => $dealers,
                ];
            });
        
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error fetching top stations: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load Dealer data'], 500);
        }
    }

    public function suspendStation($id)
    {
        $station = station::find($id);
        
        if (!$station) {
            return redirect()->back()->with('error', 'Station not found.');
        }
        
        if ($station->status === 'pending') {
            $station->status = 'active';
        } elseif ($station->status === 'active') {
            $station->status = 'suspended';
        } elseif ($station->status === 'suspended') {
            $station->status = 'active';
        }
        
        $station->save();
        
        return redirect()->route('overviewStation')->with('success', 'Station status updated successfully.');
    }

    public function deleteStation($id)
    {
        $station = station::find($id);
        if (!$station) {
            return redirect()->back()->with('error', 'StationOMC not found.');
        }
        $station->delete();
        return redirect()->route('overviewStation')->with('success', 'Station deleted successfully.');
    }

    public function showAddDealerForm()
    {
        $dealers = \DB::table('dealer')->get();
    
        return view('stations.addStation', compact('dealers'));
    }

    public function viewStationProfile($id)
    {
        $station = DB::table('station')->find($id);
        if (!$station) {
            return redirect()->route('viewStationProfile')->with('fail', 'station not found.');
        }
    
        $dealerIds = array_filter(explode(',', $station->dealer_id));
        $dealerCount = DB::table('dealer')->whereIn('id', $dealerIds)->count();

        return view('stations.viewStationProfile', [
            'station' => $station,
            'dealerCount' => $dealerCount
        ]);
    }

    public function stationDealerOverview($id, Request $request)
    {
        $station = DB::table('station')->find($id);
        if (!$station) {
            return redirect()->back()->with('fail', 'station not found.');
        }
    
        $dealerId = $station->dealer_id;
        $dealerIds = explode(',', $dealerId);

        $perPage = $request->input('per_page', 5);
        $search = $request->input('search');
    
        $dealers = DB::table('dealer')
        ->whereIn('id', $dealerIds)
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($subquery) use ($search) {
                $subquery->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('middle_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    
        return view('stations.stationDealerOverview', [
            'station' => $station,
            'dealers' => $dealers,
            'search' => $search,
            'perPage' => $perPage
        ]);
    }
    
    public function getStationDealerStats($id)
    {
        $station = DB::table('station')->find($id);
        if (!$station) {
            return redirect()->back()->with('fail', 'station not found.');
        }

        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();

        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];

        $dealerId = $station->dealer_id;
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, id)', [$dealerId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, id)', [$dealerId])
            ->whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))
            ->count();
        }
    
        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getStationDealerAverage($id)
    {

        $station = DB::table('station')->find($id);
        if (!$station) {
            return redirect()->back()->with('fail', 'station not found.');
        }
        
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];

        $dealerId = $station->dealer_id;
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, id)', [$dealerId])
            ->whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))
            ->count();
        
            $previousWeekData[] = Dealer::whereRaw('FIND_IN_SET(?, id)', [$dealerId])
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
