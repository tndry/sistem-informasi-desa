<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Setting;
use App\Models\Settings;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use App\Filament\Resources\SettingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SettingResource\RelationManagers;

class SettingResource extends Resource
{
    protected static ?string $model = Settings::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $label = 'Pengaturan Website';
    
    protected static ?string $pluralLabel = 'Pengaturan Website';

    

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Tabs::make('Pengaturan')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Informasi Dasar')
                        ->schema([
                            Forms\Components\TextInput::make('nama_desa')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\FileUpload::make('logo_desa')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->maxSize(1024), // 1MB
                                
                            Forms\Components\Textarea::make('deskripsi_desa')
                                ->rows(3)
                                ->maxLength(65535),
                            Forms\Components\TextInput::make('alamat_desa')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('telepon')
                                ->tel()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('kode_pos')
                                ->maxLength(10),
                        ]),
                    Forms\Components\Tabs\Tab::make('Lokasi')
                        ->schema([
                            Forms\Components\TextInput::make('kecamatan')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('kabupaten')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('provinsi')
                                ->maxLength(255),
                            Forms\Components\Fieldset::make('Lokasi Maps')
                                ->schema([
                                    Forms\Components\TextInput::make('koordinat_lokasi.lat')
                                        ->label('Latitude')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('koordinat_lokasi.lng')
                                        ->label('Longitude')
                                        ->numeric(),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Tampilan')
                        ->schema([
                            Forms\Components\FileUpload::make('background_image')
                                ->label('Gambar Latar')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->maxSize(2048), // 2MB
                            Forms\Components\ColorPicker::make('color_primary')
                                ->label('Warna Utama')
                                ->default('#3490dc'),
                            Forms\Components\ColorPicker::make('color_secondary')
                                ->label('Warna Sekunder')
                                ->default('#38c172'),
                        ]),
                    Forms\Components\Tabs\Tab::make('Media Sosial')
                        ->schema([
                            Forms\Components\Repeater::make('social_media')
                                ->schema([
                                    Forms\Components\Select::make('platform')
                                        ->options([
                                            'facebook' => 'Facebook',
                                            'twitter' => 'Twitter',
                                            'instagram' => 'Instagram',
                                            'youtube' => 'YouTube',
                                            'tiktok' => 'TikTok',
                                            'whatsapp' => 'WhatsApp',
                                            'telegram' => 'Telegram',
                                        ])
                                        ->required(),
                                    Forms\Components\TextInput::make('url')
                                        ->url()
                                        ->required(),
                                ])
                                ->columns(2),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('nama_desa')
                ->label('Nama Desa'),
            ImageColumn::make('logo_desa')
                ->label('Logo')
                ->visibility('public')
                ->disk('public')
                ->height(30) // optional styling
                ->width(30)
                ->circular(), 
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Terakhir Diperbarui')
                ->dateTime(),
        ])
        ->actions([
            ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            
        ])
        ->bulkActions([]);
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
