<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $navigationGroup = 'Employee Management';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->columns(3)
                    ->schema(
                        [
                            Forms\Components\TextInput::make('name')
                                ->required(),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required(),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->required()
                                ->hiddenOn('edit'),

                        ]
                    ),
                Forms\Components\Section::make('Address Information')
                    ->columns(3)
                    ->schema(
                        [
                            Forms\Components\Select::make('country_id')
                                ->relationship(name: 'country', titleAttribute: 'name')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Set $set): void {
                                    $set('state_id', null);
                                    $set('city_id', null);
                                })
                                ->required(),
                            Forms\Components\Select::make('state_id')
                                ->options(
                                    fn(Get $get): Collection =>
                                    State::query()
                                        ->where('country_id', $get('country_id'))
                                        ->get()->pluck('name', 'id'),
                                )
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Set $set): void {
                                    $set('city_id', null);
                                })
                                ->required(),
                            Forms\Components\Select::make('city_id')
                                ->options(
                                    fn(Get $get): Collection =>
                                    City::query()
                                        ->where('state_id', $get('state_id'))
                                        ->get()->pluck('name', 'id'),
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('address')
                                ->required(),
                            Forms\Components\TextInput::make('zip_code')
                                ->minLength(5)
                                ->maxLength(5)
                                ->required(),

                        ]
                    ),
                Forms\Components\Section::make('Access')
                    ->columns(3)
                    ->schema(
                        [
                            Forms\Components\MultiSelect::make('roles')
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpanFull(),
                        ]
                    ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('zip_code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
