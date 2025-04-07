<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Shoe;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ShoeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ShoeResource\RelationManagers;

class ShoeResource extends Resource
{
    protected static ?string $model = Shoe::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('price')->required()->numeric()->prefix('IDR'),
                    FileUpload::make('thumbnail')->image()->required(),
                    Repeater::make('photos')->relationship('photos')->schema([
                        FileUpload::make('photo')->required(),
                    ]),
                    Repeater::make('sizes')->relationship('sizes')->schema([
                        TextInput::make('size')->required(),
                    ]),
                ]),
                Fieldset::make('Additional')
                ->schema([
                    Textarea::make('about')->required(),
                    Select::make('is_popular')
                    ->options([
                        true => 'Popular',
                        false => 'Not Popular',
                    ])->required(),
                    Select::make('category_id')->relationship('category', 'name')
                    ->searchable()->preload()->required(),
                    Select::make('brand_id')->relationship('brand', 'name')
                    ->searchable()->preload()->required(),

                    TextInput::make('stock')->required()->numeric()->prefix('Qty')

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListShoes::route('/'),
            'create' => Pages\CreateShoe::route('/create'),
            'edit' => Pages\EditShoe::route('/{record}/edit'),
        ];
    }
}
