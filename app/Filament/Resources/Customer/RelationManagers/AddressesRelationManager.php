<?php

namespace App\Filament\Resources\Customer\RelationManagers;

use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class AddressesRelationManager extends RelationManager
{
    public static $primaryColumn = 'address_line';

    public static $relationship = 'addresses';

    public static function form(Form $form)
    {
        return $form
            ->schema([
                Components\TextInput::make('address_line')->label('Địa chỉ')->required(), Components\TextInput::make('contact_name')->label('Người liên hệ'), Components\TextInput::make('contact_phone')->label('Điện thoại'), Components\Toggle::make('is_default')->label('Mặc định'),
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('address_line')->label('Địa chỉ')->primary(), Columns\Text::make('contact_name')->label('Liên hệ'), Columns\Text::make('contact_phone')->label('Điện thoại'),
            ])
            ->filters([
                //
            ]);
    }
}
