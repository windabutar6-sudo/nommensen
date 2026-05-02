<?php

namespace App\Filament\Resources\Cooperations;

use App\Filament\Resources\Cooperations\Pages;
use App\Models\Cooperation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CooperationResource extends Resource
{
    protected static ?string $model = Cooperation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Kerja Sama';
    protected static ?string $modelLabel = 'Kerja Sama';
    protected static ?string $pluralModelLabel = 'Kerja Sama';
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Konten';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Logo Kerja Sama')
                    ->image()
                    ->disk('public')
                    ->directory('cooperations')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->required()
                    ->helperText('Upload logo mitra. Disarankan JPG/PNG ukuran kecil agar lebih cepat.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->imageSize(60),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCooperations::route('/'),
            'create' => Pages\CreateCooperation::route('/create'),
            'edit' => Pages\EditCooperation::route('/{record}/edit'),
        ];
    }
}