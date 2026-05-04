<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageViewResource\Pages;
use App\Models\PageView;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageViewResource extends Resource
{
    protected static ?string $model = PageView::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'Page Views';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('path')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->maxLength(500),
                Forms\Components\Select::make('device_type')
                    ->options([
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                        'desktop' => 'Desktop',
                    ]),
                Forms\Components\TextInput::make('browser')
                    ->maxLength(100),
                Forms\Components\TextInput::make('platform')
                    ->maxLength(100),
                Forms\Components\TextInput::make('ip_address')
                    ->maxLength(45),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('device_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mobile' => 'success',
                        'tablet' => 'warning',
                        'desktop' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('browser')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('platform')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->default('Guest'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (s)')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('device_type')
                    ->options([
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                        'desktop' => 'Desktop',
                    ]),
                Tables\Filters\SelectFilter::make('browser'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPageViews::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
