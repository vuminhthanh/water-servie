<?php

namespace App\Filament\Resources\Technician\RelationManagers;

use App\Filament\Resources\ServiceOrderResource;
use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\RelationManager;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\RecordActions;
use Filament\Resources\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    public static $title = 'Lịch sử phân công';
    public static $primaryColumn = 'id';

    public static $relationship = 'assignments';
    public function canCreate(){return false;} public function canEdit(){return false;} public function canDelete(){return false;}

    public static function form(Form $form)
    {
        return $form
            ->schema([
                // Assignment được tạo bởi AssignTechnician Action.
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('serviceOrder.order_code')->label('Đơn')->url(fn($assignment)=>ServiceOrderResource::generateUrl('edit',['record'=>$assignment->service_order_id])), Columns\Text::make('assigned_at')->label('Phân công lúc')->dateTime('d/m/Y H:i'), Columns\Text::make('status')->label('Trạng thái'), Columns\Text::make('completed_at')->label('Hoàn thành')->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->pushRecordActions([
                RecordActions\Link::make('open_order')->label('Mở đơn')->url(fn($assignment)=>ServiceOrderResource::generateUrl('edit',['record'=>$assignment->service_order_id])),
            ]);
    }
}
