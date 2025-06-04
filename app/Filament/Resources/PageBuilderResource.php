<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PageBuilder;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PageBuilderResource\Pages;
use App\Filament\Resources\PageBuilderResource\RelationManagers;

class PageBuilderResource extends Resource
{
    protected static ?string $model = PageBuilder::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Manajemen Konten';

    protected static ?string $label = 'Halaman';

    protected static ?string $pluralLabel = 'Halaman';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Halaman')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(PageBuilder::class, 'slug', ignoreRecord: true),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Builder::make('content')
                            ->label('Konten')
                            ->blocks([
                                Forms\Components\Builder\Block::make('heading')
                                    ->label('Heading')
                                    ->schema([
                                        Forms\Components\TextInput::make('content')
                                            ->label('Judul')
                                            ->required(),
                                        Forms\Components\Select::make('level')
                                            ->label('Level')
                                            ->options([
                                                'h1' => 'H1',
                                                'h2' => 'H2',
                                                'h3' => 'H3',
                                                'h4' => 'H4',
                                            ])
                                            ->default('h2'),
                                    ]),
                                Forms\Components\Builder\Block::make('paragraph')
                                    ->label('Paragraf')
                                    ->schema([
                                        Forms\Components\RichEditor::make('content')
                                            ->label('Konten')
                                            ->required(),
                                    ]),
                                Forms\Components\Builder\Block::make('image')
                                    ->label('Gambar')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Gambar')
                                            ->image()
                                            ->directory('page-builder')
                                            ->visibility('public')
                                            ->required(),
                                        Forms\Components\TextInput::make('caption')
                                            ->label('Keterangan')
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Builder\Block::make('gallery')
                                    ->label('Galeri')
                                    ->schema([
                                        Forms\Components\Repeater::make('images')
                                            ->label('Gambar')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->label('Gambar')
                                                    ->image()
                                                    ->directory('page-builder/gallery')
                                                    ->visibility('public')
                                                    ->required(),
                                                Forms\Components\TextInput::make('caption')
                                                    ->label('Keterangan')
                                                    ->maxLength(255),
                                            ])
                                            ->columns(2),
                                    ]),
                                Forms\Components\Builder\Block::make('video')
                                    ->label('Video')
                                    ->schema([
                                        Forms\Components\TextInput::make('youtube_id')
                                            ->label('ID YouTube')
                                            ->required()
                                            ->helperText('Contoh: jika URL video adalah https://www.youtube.com/watch?v=ABC123, maka ID-nya adalah ABC123'),
                                        Forms\Components\TextInput::make('caption')
                                            ->label('Keterangan')
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Builder\Block::make('file_download')
                                    ->label('File Download')
                                    ->schema([
                                        Forms\Components\FileUpload::make('file')
                                            ->label('File')
                                            ->directory('page-builder/files')
                                            ->visibility('public')
                                            ->required(),
                                        Forms\Components\TextInput::make('title')
                                            ->label('Judul')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('description')
                                            ->label('Deskripsi')
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\Builder\Block::make('divider')
                                    ->label('Pembatas')
                                    ->schema([
                                        Forms\Components\Select::make('style')
                                            ->label('Style')
                                            ->options([
                                                'solid' => 'Solid',
                                                'dashed' => 'Dashed',
                                                'dotted' => 'Dotted',
                                            ])
                                            ->default('solid'),
                                    ]),
                            ])
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Nama Halaman')
                ->searchable(),
            Tables\Columns\TextColumn::make('slug')
                ->searchable(),
            Tables\Columns\IconColumn::make('is_active')
                ->label('Status')
                ->boolean(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            //
        ])
         ->actions([
            ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            
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
            'index' => Pages\ListPageBuilders::route('/'),
            'create' => Pages\CreatePageBuilder::route('/create'),
            'edit' => Pages\EditPageBuilder::route('/{record}/edit'),
        ];
    }
}
