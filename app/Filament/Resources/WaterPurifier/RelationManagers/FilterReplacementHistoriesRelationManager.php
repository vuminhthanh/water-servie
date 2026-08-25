<?php

namespace App\Filament\Resources\WaterPurifier\RelationManagers;

use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class FilterReplacementHistoriesRelationManager extends RelationManager
{
    public static $title = 'Lịch sử thay lõi';
    public static $primaryColumn = 'new_filter_name';

    public static $relationship = 'filterReplacementHistories';
    public function canCreate(){return false;} public function canEdit(){return false;} public function canDelete(){return false;}

    public static function form(Form $form)
    {
        return $form
            ->schema([
                // Audit history is read-only.
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('replaced_at')->label('Ngày thay')->dateTime('d/m/Y H:i'), Columns\Text::make('old_filter_name')->label('Lõi cũ'), Columns\Text::make('new_filter_name')->label('Lõi mới')->primary(), Columns\Text::make('next_replace_at')->label('Thay tiếp theo')->date('d/m/Y'),
            ])
            ->filters([
                //
            ]);
    }
}
