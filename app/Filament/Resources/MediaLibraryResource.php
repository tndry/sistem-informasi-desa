<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MediaLibrary;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MediaLibraryResource\Pages;
use App\Filament\Resources\MediaLibraryResource\RelationManagers;

class MediaLibraryResource extends Resource
{
    protected static ?string $model = MediaLibrary::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Manajemen Konten';

    protected static ?string $label = 'Media';
    
    protected static ?string $pluralLabel = 'Media';

    

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Card::make()
                ->schema([
                    FileUpload::make('file_path')
                        ->label('File')
                        ->image()
                        ->required()
                        ->disk('public')
                        ->directory('media-library')
                        ->visibility('public')
                        ->maxSize(5120) // 5MB
                        ->preserveFilenames()
                        ->previewable(true)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (!$state) return;
                            
                            $path = storage_path('app/public/' . $state);
                            
                            if (file_exists($path)) {
                                $set('file_name', pathinfo($path, PATHINFO_FILENAME));
                                $set('mime_type', mime_content_type($path));
                                $set('file_size', filesize($path));
                            }
                        }),
                    
                    Forms\Components\TextInput::make('file_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('caption')
                        ->maxLength(255),
                    Forms\Components\Select::make('type')
                        ->options([
                            'image' => 'Gambar',
                            'document' => 'Dokumen',
                            'video' => 'Video',
                            'other' => 'Lainnya',
                        ])
                        ->default('image'),
                    Forms\Components\Hidden::make('mime_type'),
                    Forms\Components\Hidden::make('file_size'),
                ])
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('File')
                    ->url(fn ($record) => asset('storage/' . $record->file_path)),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe'),
                Tables\Columns\TextColumn::make('caption')
                    ->label('Keterangan')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'image' => 'Gambar',
                        'document' => 'Dokumen',
                        'video' => 'Video',
                        'other' => 'Lainnya',
                    ]),
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
            'index' => Pages\ListMediaLibraries::route('/'),
            'create' => Pages\CreateMediaLibrary::route('/create'),
            'edit' => Pages\EditMediaLibrary::route('/{record}/edit'),
        ];
    }
}
