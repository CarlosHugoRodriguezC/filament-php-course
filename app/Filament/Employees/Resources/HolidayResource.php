<?php

namespace App\Filament\Employees\Resources;

use App\Filament\Employees\Resources\HolidayResource\Pages;
use App\Filament\Employees\Resources\HolidayResource\RelationManagers;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    ->relationship('calendar', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('day')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'declined' => 'danger',
                    })
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'declined' => 'Declined',
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                    ]),
                Tables\Filters\Filter::make('day')
                    ->form(
                        [
                            Forms\Components\DatePicker::make('day_from')
                                ->placeholder('Filter by date'),
                            Forms\Components\DatePicker::make('day_to')
                                ->placeholder('Filter by date'),
                        ]
                    )->query(
                        function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['day_from'],
                                    fn($query, $day_from) => $query->where('day', '>=', $day_from)
                                )
                                ->when(
                                    $data['day_to'],
                                    fn($query, $day_to) => $query->where('day', '<=', $day_to)
                                );
                        }
                    )->indicateUsing(
                        function ($data) {
                            $indicators = [];
                            if ($data['day_from']) {
                                $indicators[] = 'From: ' . $data['day_from'];
                            }
                            if ($data['day_to']) {
                                $indicators[] = 'To: ' . $data['day_to'];
                            }

                            return $indicators;
                        }
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
