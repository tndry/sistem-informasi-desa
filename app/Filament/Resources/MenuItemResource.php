<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $label = 'Menu';

    protected static ?string $pluralLabel = 'Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Menu')
                            ->options(function ($record) {
                                // Exclude current item from parent options to prevent circular dependency
                                $query = MenuItem::query();
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }
                                return $query->pluck('title', 'id');
                            })
                            ->searchable()
                            ->placeholder('Pilih parent menu (opsional)'),
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Menu')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->maxLength(255)
                            ->helperText('Biarkan kosong jika menu ini hanya sebagai parent'),
                        Forms\Components\Select::make('target')
                            ->label('Target')
                            ->options([
                                '_self' => 'Buka di tab yang sama',
                                '_blank' => 'Buka di tab baru',
                            ])
                            ->default('_self'),
                        Forms\Components\TextInput::make('icon_class')
                            ->label('Kelas Ikon')
                            ->maxLength(255)
                            ->helperText('Misalnya: fa fa-home'),
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->integer()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent Menu')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Menu')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Parent Menu')
                    ->options(
                        MenuItem::whereNull('parent_id')->pluck('title', 'id')->toArray()
                    )
                    ->placeholder('Semua Menu'),
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
            ->reorderable('order')
            ->defaultSort('order');
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
