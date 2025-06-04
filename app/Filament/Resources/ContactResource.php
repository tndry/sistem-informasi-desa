<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Manajemen Konten';

    protected static ?string $label = 'Pesan';

    protected static ?string $pluralLabel = 'Pesan';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('subject')
                            ->label('Subjek')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->label('Pesan')
                            ->required()
                            ->rows(5),
                        Forms\Components\Toggle::make('is_read')
                            ->label('Sudah Dibaca')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Nama')
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->searchable(),
            Tables\Columns\TextColumn::make('subject')
                ->label('Subjek')
                ->searchable()
                ->limit(30),
            Tables\Columns\IconColumn::make('is_read')
                ->label('Status')
                ->boolean(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('is_read')
                ->label('Status')
                ->placeholder('Semua Pesan')
                ->trueLabel('Sudah Dibaca')
                ->falseLabel('Belum Dibaca'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('markAsRead')
                ->label('Tandai Sudah Dibaca')
                ->icon('heroicon-o-check')
                ->action(fn (Contact $record) => $record->update(['is_read' => true]))
                ->visible(fn (Contact $record) => !$record->is_read),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('markAsRead')
                    ->label('Tandai Sudah Dibaca')
                    ->icon('heroicon-o-check')
                    ->action(fn (Collection $records) => $records->each->update(['is_read' => true])),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
