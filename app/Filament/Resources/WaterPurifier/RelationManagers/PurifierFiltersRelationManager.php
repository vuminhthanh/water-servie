<?php

namespace App\Filament\Resources\WaterPurifier\RelationManagers;

use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class PurifierFiltersRelationManager extends RelationManager
{
    public static $title = 'Tình trạng lõi lọc';
    public static $primaryColumn = 'filter_name';

    public static $relationship = 'purifierFilters';
    public function canCreate(){return false;} public function canEdit(){return false;} public function canDelete(){return false;}

    public static function form(Form $form)
    {
        return $form
            ->schema([
                // Lõi được quản lý bởi nghiệp vụ thay lõi.
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('filter_position')->label('Vị trí'), Columns\Text::make('filter_name')->label('Lõi lọc')->primary(), Columns\Text::make('last_replace_at')->label('Thay gần nhất')->date('d/m/Y'), Columns\Text::make('next_replace_at')->label('Thay tiếp theo')->date('d/m/Y'), Columns\Text::make('status')->label('Trạng thái'),
            ])
            ->filters([
                //
            ]);
    }
}
