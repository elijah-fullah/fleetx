<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsAdvancedTextualRequest;
use App\Mail\PostMail;
use App\Mail\AddUser;
use App\Jobs\AddUserJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Infobip\Exception\ApiException;

class UserController extends Controller
{
    public function overviewUser(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
    
        $allUser = user::select('user.id', 'user.first_name', 'user.middle_name', 'user.last_name', 'user.category', 'user.email', 'user.phone', 'user.status')
        ->where(function($query) use ($search) {
            $query->where('user.first_name', 'like', '%'.$search.'%')
            ->orWhere('user.last_name', 'like', '%'.$search.'%')
            ->orWhere('user.email', 'like', '%'.$search.'%')
            ->orWhere('user.phone', 'like', '%'.$search.'%');
        })

        ->orderBy('user.id', 'desc')
        ->paginate($perPage);
        return view('users.overviewUser', compact('allUser', 'search', 'perPage'));
    }

    public function addUser() { return view('users.addUser'); }

    public function addUsers(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'category' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
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

        $plainPassword = Str::random(12);
        $hashedPassword = bcrypt($plainPassword);

        DB::insert(
            "INSERT INTO user (first_name, middle_name, last_name, category, email, phone, password, device, is_online, last_login, created_at, updated_at, userName, userId, userRole, editedUserName, editedUserId, editedUserRole, editedDevice, editedTime, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['first_name'],
                $data['middle_name'],
                $data['last_name'],
                $data['category'],
                $data['email'],
                $data['phone'],
                $hashedPassword,
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

        $data['password'] = $plainPassword;

        Mail::to($data['email'])->send(new AddUser($data));

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
            return redirect()->route('addUser')->with('fail', 'User added but SMS failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'User added successfully. Email and SMS sent.');
    }

    public function editUser($id)
    {
        $user = DB::table('user')->find($id);
        if (!$user) {
            return redirect()->route('overviewUser')->with('fail', 'User not found.');
        }
        return view('users.editUser', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'category' => 'required',
        ]);
    
        $data['device'] = $request->header('User-Agent');
        $data['is_online'] = false;
        $data['last_login'] = null;
        DB::table('user')
        ->where('id', $id)
        ->update($data);
        return redirect()->route('overviewUser')->with('success', 'User details updated successfully.');
    }

    public function getUserStats()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
        
            $currentWeekData[] = user::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
        
            $previousWeekData[] = user::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
        }

        return response()->json([
            'labels' => $dates,
            'currentWeek' => $currentWeekData,
            'previousWeek' => $previousWeekData
        ]);
    }

    public function getUserAverage()
    {
        $startOfCurrentWeek = now()->startOfWeek();
        $startOfPreviousWeek = now()->subWeek()->startOfWeek();
    
        $dates = [];
        $currentWeekData = [];
        $previousWeekData = [];
    
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfCurrentWeek->copy()->addDays($i)->format('D');
            $currentWeekData[] = user::whereDate('created_at', $startOfCurrentWeek->copy()->addDays($i))->count();
            $previousWeekData[] = user::whereDate('created_at', $startOfPreviousWeek->copy()->addDays($i))->count();
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

    public function suspendUser($id)
    {
        $user = user::find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        
        if ($user->status === 'pending') {
            $user->status = 'active';
        } elseif ($user->status === 'active') {
            $user->status = 'suspended';
        } elseif ($user->status === 'suspended') {
            $user->status = 'active';
        }
        
        $user->save();
        
        return redirect()->route('overviewUser')->with('success', 'User status updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = user::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $user->delete();
        return redirect()->route('overviewUser')->with('success', 'User deleted successfully.');
    }

    public function viewUserProfile($id)
    {
        $user = DB::table('user')->find($id);
        if (!$user) {
            return redirect()->route('viewUserProfile')->with('fail', 'User not found.');
        }

    
        return view('users.viewUserProfile', [
            'user' => $user
        ]);
    }
}
