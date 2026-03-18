<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        if(isset($request->name) && isset($request->email) && isset($request->phone))
        {
            //dd($request->email);
            //dd('In Send method');
            // Email body
            //$data = ['email' => $request->email];
            $email1 = $request->email;
            $emailContent = "Contact Us Form Enquiry:\n\n"
                        . "Name: {$request->name}\n"
                        . "Email: {$request->email}\n"
                        . "Phone: {$request->phone}\n"
                        . "Message:\n{$request->message}";

            // Send email
            Mail::raw($emailContent, function ($message) use ($request) {
                //$email1 = $data->email;
                $message->to('saistarpvtltd@gmail.com')  // replace with your receiving email
                        ->subject('Contact US Enquiry');
            });

            return redirect('https://www.saistarimpex.com/contact.html?success=1');
        } else{
            return redirect('https://www.saistarimpex.com/contact.html?datamissing=1');
        } 

    }
}
