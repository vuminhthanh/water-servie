<div>
    <x-filament::app-header
        :breadcrumbs="static::getBreadcrumbs()"
        :title="$title"
    />

    <x-filament::app-content class="space-y-6">
        <x-filament::card>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-lg font-medium text-gray-900">
                        {{ optional($record->product)->name ?: 'Sản phẩm chưa xác định' }}{{ $record->serial_number ? ' — ' . $record->serial_number : '' }}
                    </div>
                    <div class="text-sm text-gray-500">Toàn bộ lịch sử vận hành và bảo trì của máy.</div>
                </div>

                <x-filament::button :href="static::getResource()::generateUrl('edit', ['record' => $record])">
                    Quay lại thông tin máy
                </x-filament::button>
            </div>
        </x-filament::card>

        <x-filament::resources.relations
            :owner="$record"
            :relations="static::getResource()::historyRelations()"
        />
    </x-filament::app-content>
</div>
