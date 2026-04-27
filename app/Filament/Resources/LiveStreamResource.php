<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveStreamResource\Pages;
use App\Filament\Resources\LiveStreamResource\RelationManagers;
use App\Models\LiveStream;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiveStreamResource extends Resource
{
    protected static ?string $model = LiveStream::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Live Stream';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stream Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->default('Live Stream')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('YouTube Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=VIDEO_ID or https://youtu.be/VIDEO_ID')
                            ->helperText('Paste your YouTube video or live stream URL. The video ID will be extracted automatically.')
                            ->columnSpanFull()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $state, $matches);
                                    if (isset($matches[1])) {
                                        $set('youtube_video_id', $matches[1]);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('youtube_video_id')
                            ->label('YouTube Video ID')
                            ->helperText('Automatically extracted from the URL above')
                            ->disabled()
                            ->dehydrated(),
                    ]),

                Forms\Components\Section::make('Stream Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_live')
                            ->label('Stream is Live')
                            ->helperText('Toggle this on when you go live')
                            ->default(false)
                            ->live(),

                        Forms\Components\Toggle::make('auto_detect')
                            ->label('Auto-detect Live Status')
                            ->helperText('Automatically detect when you go live on YouTube using the API')
                            ->default(false)
                            ->live(),

                        Forms\Components\DateTimePicker::make('stream_started_at')
                            ->label('Stream Started At')
                            ->displayFormat('M d, Y H:i')
                            ->visible(fn ($get) => $get('is_live')),

                        Forms\Components\DateTimePicker::make('stream_ended_at')
                            ->label('Stream Ended At')
                            ->displayFormat('M d, Y H:i')
                            ->visible(fn ($get) => !$get('is_live')),
                    ]),

                Forms\Components\Section::make('YouTube API Configuration')
                    ->description('Required for auto-detecting when your channel goes live. Get your API key from the Google Cloud Console.')
                    ->icon('heroicon-o-key')
                    ->visible(fn ($get) => (bool) $get('auto_detect'))
                    ->schema([
                        Forms\Components\TextInput::make('youtube_channel_id')
                            ->label('YouTube Channel ID')
                            ->placeholder('UCxxxxxxxxxxxxxxxxxxxxxxxxx')
                            ->helperText('Found in YouTube Studio → Settings → Channel → Advanced settings')
                            ->maxLength(255)
                            ->required(fn ($get) => (bool) $get('auto_detect')),

                        Forms\Components\TextInput::make('youtube_api_key')
                            ->label('YouTube Data API v3 Key')
                            ->placeholder('AIzaSy...')
                            ->helperText('Create a key at console.cloud.google.com → APIs & Services → Credentials')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn ($get) => (bool) $get('auto_detect')),

                        Forms\Components\Select::make('check_interval_minutes')
                            ->label('Check Interval')
                            ->helperText('How often to poll the YouTube API for live status')
                            ->options([
                                1  => 'Every 1 minute',
                                2  => 'Every 2 minutes',
                                5  => 'Every 5 minutes',
                                10 => 'Every 10 minutes',
                                15 => 'Every 15 minutes',
                                30 => 'Every 30 minutes',
                            ])
                            ->default(5)
                            ->required(fn ($get) => (bool) $get('auto_detect')),

                        Forms\Components\Placeholder::make('api_setup_guide')
                            ->label('Setup Guide')
                            ->content(new \Illuminate\Support\HtmlString('
                                <ol class="list-decimal list-inside space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                    <li>Go to <a href="https://console.cloud.google.com" target="_blank" class="text-primary-600 underline">console.cloud.google.com</a></li>
                                    <li>Create a new project (or select an existing one)</li>
                                    <li>Navigate to <strong>APIs &amp; Services → Library</strong></li>
                                    <li>Search for and enable <strong>YouTube Data API v3</strong></li>
                                    <li>Go to <strong>APIs &amp; Services → Credentials</strong></li>
                                    <li>Click <strong>Create Credentials → API Key</strong></li>
                                    <li>Copy the key and paste it above</li>
                                    <li>To find your Channel ID: YouTube Studio → Settings → Channel → Advanced settings</li>
                                </ol>
                            ')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_live')
                    ->label('Live')
                    ->boolean()
                    ->trueIcon('heroicon-o-signal')
                    ->falseIcon('heroicon-o-signal-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('youtube_video_id')
                    ->label('Video ID')
                    ->limit(15)
                    ->searchable(),

                Tables\Columns\IconColumn::make('auto_detect')
                    ->label('Auto-detect')
                    ->boolean(),

                Tables\Columns\TextColumn::make('stream_started_at')
                    ->label('Started')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_live')
                    ->label('Live Status')
                    ->placeholder('All streams')
                    ->trueLabel('Live streams')
                    ->falseLabel('Offline streams'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
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
            'index' => Pages\ListLiveStreams::route('/'),
            'create' => Pages\CreateLiveStream::route('/create'),
            'edit' => Pages\EditLiveStream::route('/{record}/edit'),
        ];
    }
}
