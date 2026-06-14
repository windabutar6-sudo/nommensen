<?php

namespace App\Filament\Resources\Footers;

use App\Filament\Resources\Footers\Pages;
use App\Models\Footer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class FooterResource extends Resource
{
    protected static ?string $model = Footer::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Footer';
    protected static ?string $modelLabel = 'Footer';
    protected static ?string $pluralModelLabel = 'Pengaturan Footer';
    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return ! static::getModel()::query()->exists();
    }
public static function form(Schema $schema): Schema
{
    return $schema
        ->schema([
            \Filament\Schemas\Components\Section::make('Informasi Kontak')
                ->schema([
                    Forms\Components\TextInput::make('alamat')
                        ->label('Alamat Lengkap')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('contoh: Jl. Pendidikan No. 1, Pematangsiantar, Sumatera Utara 21121'),

                    Forms\Components\TextInput::make('link_gmaps')
                        ->label('Link Google Maps (Embed URL)')
                        ->url()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('https://www.google.com/maps/embed?pb=...'),

                    Forms\Components\TextInput::make('email')
                        ->label('Email Kontak')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('contoh: info@b-university.ac.id'),

                    Forms\Components\TextInput::make('wa')
                        ->label('Nomor WhatsApp')
                        ->required()
                        ->maxLength(255)
                        ->prefix('+62')
                        ->placeholder('contoh: 81234567890'),
                ])->columns(2),

            \Filament\Schemas\Components\Section::make('Media Sosial')
                ->schema([
                    Forms\Components\TextInput::make('link_instagram')
                        ->label('Instagram')
                        ->url()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('https://instagram.com/buniversity'),

                    Forms\Components\TextInput::make('link_youtube')
                        ->label('YouTube')
                        ->url()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('https://youtube.com/@buniversity'),

                    Forms\Components\TextInput::make('link_linkedin')
                        ->label('LinkedIn')
                        ->url()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('https://linkedin.com/school/buniversity'),

                    Forms\Components\TextInput::make('link_facebook')
                        ->label('Facebook')
                        ->url()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('https://facebook.com/buniversity'),
                ])->columns(2),

            \Filament\Schemas\Components\Section::make('Logo Universitas')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Logo Universitas')
                        ->image()
                        ->directory('footers')
                        ->visibility('public')
                        ->imagePreviewHeight('120')
                        ->maxSize(2048)
                        ->required()
                        ->helperText('Logo putih/transparan paling cocok untuk footer.')
                        ->columnSpanFull(),
                ]),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Logo')
                    ->disk('public')
                    ->height(50),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(fn (?string $state): ?string => $state)
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email disalin!')
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('wa')
                    ->label('WhatsApp')
                    ->copyable()
                    ->copyMessage('Nomor WA disalin!')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->prefix('+62 '),

                Tables\Columns\TextColumn::make('link_instagram')
                    ->label('Instagram')
                    ->url(fn ($record) => $record->link_instagram, true)
                    ->icon('heroicon-o-link')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Buka' : '-')
                    ->color('info'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFooters::route('/'),
            'create' => Pages\CreateFooter::route('/create'),
            'edit' => Pages\EditFooter::route('/{record}/edit'),
        ];
    }
}