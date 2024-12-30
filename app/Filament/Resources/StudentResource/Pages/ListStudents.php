<?php
//
//namespace App\Filament\Resources\StudentResource\Pages;
//
//use App\Filament\Resources\StudentResource;
//use App\Imports\StudentsImport;
//use Filament\Actions;
//use Filament\Forms\Components\FileUpload;
//use Filament\Notifications\Notification;
//use Filament\Resources\Pages\ListRecords;
//use Maatwebsite\Excel\Facades\Excel;
//
//class ListStudents extends ListRecords
//{
//    protected static string $resource = StudentResource::class;
//
//    protected function getHeaderActions(): array
//    {
//        return [
//            Actions\CreateAction::make(),
//            Actions\Action::make('ImportStudents')
//                ->label('Import')
//                ->color('danger')
////                ->icon('heroicon-s-import')
//                ->form([
//                    FileUpload::make('attachment'),
//                ])
//                ->action(function (array $data) {
//                    $file = public_path('storage' . $data['attachment']);
//                    Excel::import(new StudentsImport, $file);
//                    Notification::make()
//                        ->title('Students imported successfully')
//                        ->color('success')
//                        ->success()
//                        ->send();
//                })
//        ];
//    }
//}

namespace App\Filament\Resources\StudentResource\Pages;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListStudents extends ListRecords
{
    protected static string $resource = \App\Filament\Resources\StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('ImportStudents')
                ->label('Import')
                ->color('danger')
                ->form([
                    FileUpload::make('attachment'),
                ])
                ->action(function (array $data) {
                    $file = public_path('storage/' . $data['attachment']);
                    Excel::import(new \App\Imports\StudentsImport(), $file);
                    \Filament\Notifications\Notification::make()
                        ->title('Students imported successfully')
                        ->color('success')
                        ->success()
                        ->send();
                })
        ];
    }
}
