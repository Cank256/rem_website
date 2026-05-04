<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
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
        // Send password reset link to the newly created user
        $status = Password::sendResetLink(
            ['email' => $this->record->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            Notification::make()
                ->success()
                ->title('User created successfully')
                ->body('A password reset link has been sent to ' . $this->record->email)
                ->send();
        } else {
            Notification::make()
                ->warning()
                ->title('User created')
                ->body('User was created but the password reset email could not be sent. Please send it manually.')
                ->send();
        }
    }
}
