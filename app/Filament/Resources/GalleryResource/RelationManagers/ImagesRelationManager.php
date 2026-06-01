<?php

namespace App\Filament\Resources\GalleryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Gallery Images';

    protected static ?string $icon = 'heroicon-o-photo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('gallery-images')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(2048)
                    ->helperText('Upload an image (max 2MB). Use the image editor to crop or adjust.'),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->helperText('Optional: Add a title for this image'),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull()
                    ->helperText('Optional: Add a description for this image'),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first in the gallery'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->square()
                    ->size(100),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->placeholder('No title'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Image')
                    ->icon('heroicon-o-plus'),
                Tables\Actions\Action::make('bulk_upload')
                    ->label('Bulk Upload')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('Images')
                            ->image()
                            ->multiple()
                            ->required()
                            ->disk('public')
                            ->directory('gallery-images')
                            ->maxSize(2048)
                            ->maxFiles(50)
                            ->reorderable()
                            ->preserveFilenames()
                            ->helperText('Upload multiple images at once (max 50 images, 2MB each). Drag to reorder.'),
                    ])
                    ->action(function (array $data, RelationManager $livewire): void {
                        $gallery = $livewire->getOwnerRecord();
                        $sortOrder = $gallery->images()->max('sort_order') ?? 0;
                        
                        if (isset($data['images']) && is_array($data['images'])) {
                            foreach ($data['images'] as $imagePath) {
                                $sortOrder++;
                                $gallery->images()->create([
                                    'image_path' => $imagePath,
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                        }
                    })
                    ->successNotificationTitle('Images uploaded successfully')
                    ->modalWidth('lg'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->emptyStateHeading('No images yet')
            ->emptyStateDescription('Add images to this gallery using the buttons above.')
            ->emptyStateIcon('heroicon-o-photo');
    }
}
