<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate a random password
        $data['password'] = Hash::make(Str::random(32));
        
        return $data;
    }

    protected function afterCreate(): void
    {
        try {
            // Generate a password reset token manually
            $token = Str::random(64);
            
            // Store the token in the password_reset_tokens table
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $this->record->email],
                [
                    'email' => $this->record->email,
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now(),
                ]
            );

            // Create the reset URL without signed URL
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $this->record->email,
            ], false));

            // Send the email
            Mail::send('emails.password-reset', [
                'resetUrl' => $resetUrl,
                'user' => $this->record,
            ], function ($message) {
                $message->to($this->record->email)
                    ->subject('Set Your Password - ' . config('app.name'));
            });

            Notification::make()
                ->success()
                ->title('User created successfully')
                ->body('A password reset link has been sent to ' . $this->record->email)
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->warning()
                ->title('User created')
                ->body('User was created but the password reset email could not be sent: ' . $e->getMessage())
                ->send();
        }
    }
}
