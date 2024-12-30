<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('update-password')
                ->form([
                    TextInput::make('password')->password()->required()->confirmed(),
                    TextInput::make('password_confirmation')->password()->required(),
                ])->action(function (array $data) {

                    $this->record->update(['password' => $data['password']]);

                    Notification::make()
                        ->title('User password changed')
                        ->success()
                        ->send();

                }),

            Actions\DeleteAction::make(),
        ];
    }
}
