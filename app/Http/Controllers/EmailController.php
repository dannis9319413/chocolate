<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $email = $request->input('email');

        Mail::send('emails.test', ['email' => $email], function ($message) use ($email) {
            $message->from('Slimcocotw@gmail.com', 'Slimcoco');
            $message->to($email);
            $message->subject('感謝您的購買');
        });

        return response()->json(['message' => 'Email sent successfully']);
    }
}
