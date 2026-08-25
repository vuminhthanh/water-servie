<?php

namespace App\Filament\Resources\Customer\RelationManagers;

use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class WaterPurifiersRelationManager extends RelationManager
{
    public static $primaryColumn = 'serial_number';

    public static $relationship = 'waterPurifiers';
    public function canCreate(){return false;} public function canEdit(){return false;} public function canDelete(){return false;}

    public static function form(Form $form)
    {
        return $form
            ->schema([
                // Read-only summary.
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('product.name')->label('Sản phẩm')->primary(), Columns\Text::make('serial_number')->label('Serial / mã định danh'), Columns\Text::make('status')->label('Tình trạng sử dụng'), Columns\Text::make('next_service_at')->label('Dịch vụ tiếp theo')->date('d/m/Y'),
            ])
            ->filters([
                //
            ]);
    }
}
