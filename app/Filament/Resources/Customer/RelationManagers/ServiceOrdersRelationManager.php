<?php

namespace App\Filament\Resources\Customer\RelationManagers;

use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class ServiceOrdersRelationManager extends RelationManager
{
    public static $primaryColumn = 'order_code';

    public static $relationship = 'serviceOrders';
    public function canCreate(){return false;} public function canEdit(){return false;} public function canDelete(){return false;}

    public static function form(Form $form)
    {
        return $form
            ->schema([
                // Read-only: tạo đơn qua ServiceOrderResource để không bỏ qua Action.
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('order_code')->label('Mã đơn')->primary(), Columns\Text::make('order_type')->label('Loại'), Columns\Text::make('scheduled_at')->label('Lịch hẹn')->dateTime('d/m/Y H:i'), Columns\Text::make('status')->label('Trạng thái'), Columns\Text::make('total_amount')->label('Tổng tiền')->formatUsing(fn($value)=>number_format((float)$value,0,',','.').' ₫'),
            ])
            ->filters([
                //
            ]);
    }
}
