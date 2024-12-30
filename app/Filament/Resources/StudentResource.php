<?php

namespace App\Filament\Resources;

use App\Exports\StudentsExport;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\Pages\GenerateQrCode;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;

//use Filament\Actions\Action;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;

// use Filament\Notifications\Collection;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Filters\Filter;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'Academic Management';


    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')->required()->autofocus()->minLength(2),
                TextInput::make('email')->email(),
                TextInput::make('password')->required()->password(),


                Select::make('class_id')
                    ->relationship(name: 'class', titleAttribute: 'name')->native(false)
                    ->preload()
                    ->live()
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('section_id')
                    ->options(function (Get $get) {
                        return Section::query()
                            ->where('class_id', $get('class_id'))
                            ->pluck('name', 'id')
                            ->all(); // Converts to an array
                    })
                    ->native(false)
                    ->preload()
                    ->live()
                    ->searchable()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //

                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('class.name')->label('Class Name'),
                TextColumn::make('section.name')->label('Section Name'),


            ])
            ->filters([
                Filter::make('class-section-filter')
                    ->form([
                        Select::make('class_id')
                            ->label('Class')
                            ->placeholder('Select Class')
                            ->options(Classes::all()->pluck('name', 'id')),

                        Select::make('section_id')
                            ->label('Section')
                            ->placeholder('Select Section')
                            ->options(function (Get $get) {
                                $classId = $get('class_id');
                                if ($classId) {
                                    return Section::where('class_id', $classId)->pluck('name', 'id');
                                }
                            })
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['class_id'], function ($query) use ($data) {
                            return $query->where('class_id', $data['class_id']);
                        })->when($data['section_id'], function ($query) use ($data) {
                            return $query->where('section_id', $data['section_id']);
                        });
                    }),
            ])
            ->actions([
                Action::make('downloadPDF')->url(fn(Student $student): string => route('student.pdf.generate', $student)),
                Action::make('qrCode')->url(fn(Student $record): string => static::getUrl('qrCode', ['record' => $record])),

                Tables\Actions\EditAction::make(),
//                    Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('export')
                        ->label('Export To Excel')
                        ->action(function (Collection $records) {
                            return Excel::download(new StudentsExport($records), 'students.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'qrCode' => Pages\GenerateQrCode::route('/{record}/qrCode'),
        ];
    }
}
