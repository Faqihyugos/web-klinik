<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientAppoinmentResource\Pages;
use App\Filament\Resources\PatientAppoinmentResource\RelationManagers;
use App\Models\PatientAppoinment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Support\Facades\Auth;

class PatientAppoinmentResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PatientAppoinment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->required(),
                Forms\Components\Select::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date_of_appoinment')
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('prescription')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'dibuat' => 'Jadwal dibuat',
                        'selesai periksa' => 'Selesai Periksa',
                        'obat sudah diserahkan' => 'Obat Sudah Diserahkan',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $isDoctor = Auth::user()->hasRole('Dokter');
                $isApoteker = Auth::user()->hasRole('Apoteker');
                if ($isDoctor) {
                    $userId = Auth::user()->id;
                    $query->where('doctor_id', $userId);
                }
                if ($isApoteker) {
                    $query->where('status', 'selesai periksa')->orWhere('status', 'obat sudah diserahkan');
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_appoinment')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPatientAppoinments::route('/'),
            'create' => Pages\CreatePatientAppoinment::route('/create'),
            'edit' => Pages\EditPatientAppoinment::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
}
