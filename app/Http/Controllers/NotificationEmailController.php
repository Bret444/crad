<?php

namespace App\Http\Controllers;

use App\Mail\NotificationEmailConfirmation;
use App\Models\NotificationEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NotificationEmailController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $existing = NotificationEmail::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->confirmed) {
                return back()->with('notif_error', 'This email is already subscribed.');
            }
            $token = $existing->token;
        } else {
            $token = Str::random(40);
            NotificationEmail::create([
                'email'     => $request->email,
                'token'     => $token,
                'confirmed' => false,
            ]);
        }

        $baseUrl = rtrim(config('app.url'), '/');
        $confirmUrl = $baseUrl . '/admin/notification-emails/confirm/' . $token;
        Mail::to($request->email)->send(new NotificationEmailConfirmation($confirmUrl));

        return back()->with('notif_success', 'Confirmation email sent to ' . $request->email);
    }

    public function confirm(string $token)
    {
        try {
            $record = NotificationEmail::where('token', $token)->firstOrFail();
            
            if (!$record->confirmed) {
                $record->update(['confirmed' => true]);
                
                // Check if user already exists
                $existingUser = User::where('email', $record->email)->first();
                
                if (!$existingUser) {
                    $defaultPassword = Str::random(12);
                    
                    User::create([
                        'name'     => explode('@', $record->email)[0],
                        'email'    => $record->email,
                        'password' => Hash::make($defaultPassword),
                        'role'     => 'admin',
                        'is_admin' => 1,
                    ]);
                    
                    return view('emails.notification_email_confirmed', [
                        'password' => $defaultPassword,
                        'email' => $record->email
                    ]);
                }
            }

            return view('emails.notification_email_confirmed', ['email' => $record->email]);
        } catch (\Exception $e) {
            return response()->view('emails.notification_email_confirmed', [
                'email' => 'Unknown',
                'error' => 'Confirmation completed but there was an issue: ' . $e->getMessage()
            ], 200);
        }
    }

    public function destroy(int $id)
    {
        $record = NotificationEmail::findOrFail($id);
        
        if ($record->confirmed) {
            User::where('email', $record->email)->delete();
        }
        
        $record->delete();
        return back()->with('notif_success', 'Email removed successfully.');
    }
}
