<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SermonResource\Pages;
use App\Filament\Resources\SermonResource\RelationManagers;
use App\Models\Sermon;
use App\Models\LiveStream;
use App\Services\YouTubeService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class SermonResource extends Resource
{
    protected static ?string $model = Sermon::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                        $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null
                    ),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules(['alpha_dash']),
                Forms\Components\TextInput::make('speaker_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_preached')
                    ->required()
                    ->native(false)
                    ->displayFormat('M d, Y')
                    ->maxDate(now()),
                Forms\Components\TextInput::make('youtube_url')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->helperText('Enter a valid YouTube URL'),
                Forms\Components\TextInput::make('audio_url')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com/audio.mp3')
                    ->helperText('Enter a valid audio file URL'),
                Forms\Components\Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('speaker_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_preached')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('youtube_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('audio_url')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('syncYouTube')
                    ->label('Sync from YouTube')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Sync YouTube Live Streams')
                    ->modalDescription('This will import previous live streams from your YouTube channel as sermons. Existing sermons will not be duplicated.')
                    ->modalSubmitActionLabel('Sync Now')
                    ->action(function () {
                        // Get YouTube credentials from LiveStream model
                        $liveStream = LiveStream::first();
                        
                        if (!$liveStream || !$liveStream->youtube_channel_id || !$liveStream->youtube_api_key) {
                            Notification::make()
                                ->title('Configuration Missing')
                                ->body('Please configure YouTube Channel ID and API Key in the Live Stream settings first.')
                                ->danger()
                                ->send();
                            return;
                        }

                        try {
                            $youtubeService = new YouTubeService(
                                $liveStream->youtube_api_key,
                                $liveStream->youtube_channel_id
                            );

                            $liveStreams = $youtubeService->getPreviousLiveStreams(50);

                            if (empty($liveStreams)) {
                                Notification::make()
                                    ->title('No Live Streams Found')
                                    ->body('No previous live streams were found on the YouTube channel.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            $imported = 0;
                            $skipped = 0;

                            foreach ($liveStreams as $stream) {
                                // Check if sermon already exists
                                $exists = Sermon::where('youtube_url', $stream['youtube_url'])->exists();

                                if ($exists) {
                                    $skipped++;
                                    continue;
                                }

                                // Create sermon from live stream
                                $datePreached = $stream['actual_start_time'] 
                                    ? \Carbon\Carbon::parse($stream['actual_start_time'])
                                    : \Carbon\Carbon::parse($stream['published_at']);

                                // Generate unique slug by appending video ID if slug already exists
                                $baseSlug = Str::slug($stream['title']);
                                $slug = $baseSlug;
                                $counter = 1;
                                
                                while (Sermon::where('slug', $slug)->exists()) {
                                    $slug = $baseSlug . '-' . $stream['video_id'];
                                    if (Sermon::where('slug', $slug)->exists()) {
                                        $slug = $baseSlug . '-' . $counter;
                                        $counter++;
                                    } else {
                                        break;
                                    }
                                }

                                Sermon::create([
                                    'title' => $stream['title'],
                                    'slug' => $slug,
                                    'speaker_name' => 'Pastor', // Default speaker
                                    'date_preached' => $datePreached->toDateString(),
                                    'youtube_url' => $stream['youtube_url'],
                                    'youtube_video_id' => $stream['video_id'],
                                    'imported_from_youtube' => true,
                                    'description' => $stream['description'] ? Str::limit($stream['description'], 500) : null,
                                ]);

                                $imported++;
                            }

                            Notification::make()
                                ->title('Sync Completed')
                                ->body("Imported {$imported} new sermon(s). Skipped {$skipped} existing sermon(s).")
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Sync Failed')
                                ->body('An error occurred: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
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
            'index' => Pages\ListSermons::route('/'),
            'create' => Pages\CreateSermon::route('/create'),
            'edit' => Pages\EditSermon::route('/{record}/edit'),
        ];
    }
}
