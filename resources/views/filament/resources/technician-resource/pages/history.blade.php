<div>
    <x-filament::app-header :breadcrumbs="static::getBreadcrumbs()" :title="$title" />

    <x-filament::app-content class="space-y-6">
        <x-filament::card>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-lg font-medium text-gray-900">
                        {{ optional($record->user)->name ?: $record->technician_code }}
                    </div>
                    <div class="text-sm text-gray-500">{{ $record->technician_code }} — Lịch sử công việc đã được phân công.</div>
                </div>

                <x-filament::button :href="static::getResource()::generateUrl('edit', ['record' => $record])">
                    Quay lại thông tin kỹ thuật viên
                </x-filament::button>
            </div>
        </x-filament::card>

        <x-filament::resources.relations
            :owner="$record"
            :relations="static::getResource()::historyRelations()"
        />
    </x-filament::app-content>
</div>
