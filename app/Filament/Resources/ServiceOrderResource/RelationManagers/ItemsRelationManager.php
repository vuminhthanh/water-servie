<?php

namespace App\Filament\Resources\ServiceOrderResource\RelationManagers;

use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    public static $primaryColumn = 'name';

    public static $relationship = 'items';

    public static $title = 'Sản phẩm / dịch vụ đã thêm';

    public function canCreate(){return false;} public function canEdit(){return false;} public function canDelete(){return false;}

    public static function form(Form $form)
    {
        return $form->schema([]);
    }

    public static function table(Table $table)
    {
        return $table->columns([
            Columns\Text::make('name')->label('Tên')->primary(),
            Columns\Text::make('item_type')->label('Loại')->options(['product'=>'Sản phẩm','service'=>'Dịch vụ']),
            Columns\Text::make('quantity')->label('Số lượng'),
            Columns\Text::make('unit_price')->label('Đơn giá')->formatUsing(fn($value)=>number_format((float)$value,0,',','.').' ₫'),
            Columns\Text::make('discount_amount')->label('Giảm giá')->formatUsing(fn($value)=>number_format((float)$value,0,',','.').' ₫'),
            Columns\Text::make('total_amount')->label('Thành tiền')->formatUsing(fn($value)=>number_format((float)$value,0,',','.').' ₫'),
            Columns\Text::make('note')->label('Ghi chú'),
        ]);
    }
}
