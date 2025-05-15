<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddOMCController extends Controller
{
    public function addOMC(Request $request)
    {
        $data = $request -> validate([
            'first_name' => 'required',
            'middle_name' => 'not required',
            'last_name' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'licence_no' => 'required',
            'status' => 'required',
        ]);
        dd('ok');
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
}
