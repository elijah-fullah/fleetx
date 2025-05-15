<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsAdvancedTextualRequest;
use App\Mail\PostMail;
use App\Mail\AddOMC;
use App\Jobs\AddOMCJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Infobip\Exception\ApiException;

class MainController extends Controller
{
    public function dashboard() { return view('dashboard.dashboard'); }

    public function forget() { return view('auth.forget'); }

    public function login() { return view('auth.index'); }
    
    public function logins(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $email = $request->input('email');
        $key = 'login-attempts:' . $email;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withInput()->withErrors([
                'email' => "Too many login attempts. Try again in $seconds seconds.",
            ]);
        }
    
        if (Auth::attempt(['email' => $email, 'password' => $request->input('password')])) {
            RateLimiter::clear($key); 
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
    
        RateLimiter::hit($key, 60); 
        return back()->withInput()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function addStation() { return view('stations.add'); }
    public function manageStation() { return view('stations.manage'); }

    public function addAttendant() { return view('attendants.add'); }
    public function manageAttendant() { return view('attendants.manage'); }

    public function maps() { return view('maps'); }
    public function sensors() { return view('sensors'); }

    public function addUser() { return view('users.add'); }
    public function manageUser() { return view('users.manage'); }

    public function reports() { return view('reports'); }

    public function appearance() { return view('appearance'); }
    public function settings() { return view('settings'); }
}


/*

public function sendSMS(Request $request) {
        $configuration = new Configuration(
            host: 'https://1gz3e9.api.infobip.com/sms/2/text/advanced',
            apiKey: '27e98f12e85460e932de65bd5bc1e517-9206b4e7-d1d0-4cb6-923c-3402d8002772'
        );
    
        $SmsApi = new SmsApi(config: $configuration);
    
        $message = new SmsTextualMessage(
            destinations: [new SmsDestination(to: $request->phone)],
            from: 'Fleet XP',
            text: $request->message
        );
    
        $smsRequest = new SmsAdvancedTextualRequest(messages: [$message]);
    
        try {
            $smsResponse = $SmsApi->sendSmsMessage($smsRequest);
            return redirect()->route('overviewOMC')->with('success', 'SMS sent successfully');
        } catch (ApiException $e) {
            return redirect()->route('addOMC')->with('fail', $e->getMessage());
        }
    }
    public function sendEmail(Request $request)
    {
        $data = [
            'email' => $request->email,
            'message' => $request->message,
        ];

        Mail::to($data['email'])->send(new PostMail($data));

        return redirect()->back()->with('success', 'Email sent successfully!');
    }

    public function addOMCS(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|min:3',
            'middle_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'address' => 'required|min:5',
            'email' => 'required|email',
            'phone' => 'required|min:9',
            'licence_no' => 'required|min:3'
        ]);

        //Mail::to(env('MAIL_TO_ADDRESS'))->send(new AddOMC($data));
        $job = (new AddOMCJob($data));
        dispatch($job);
        return redirect()->back()->with('success', 'OMC added successfully');
    }


*/