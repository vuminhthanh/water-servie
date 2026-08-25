<?php

namespace App\Filament\Resources\ServiceOrderResource\Pages;

use App\Filament\Resources\ServiceOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceOrders extends ListRecords
{
    public static $resource = ServiceOrderResource::class;

    public function canDelete()
    {
        return false;
    }

    public function canDeleteSelected()
    {
        return false;
    }

    public function deleteSelected()
    {
        abort(403, 'Đơn dịch vụ đã khởi tạo không được phép xóa.');
    }

    public static function getQuery()
    {
        return parent::getQuery()
            ->with(['customer', 'purifier.product', 'assignments' => fn ($query) => $query
                ->latest('assigned_at'), 'assignments.technician'])
            ->orderByDesc('scheduled_at')
            ->orderByDesc('created_at');
    }
}
