<?php

namespace App\Actions\PurifierFilters;

use App\Enums\PurifierFilterStatus;
use App\Models\FilterReplacementHistory;
use App\Models\Product;
use App\Models\PurifierFilter;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ReplacePurifierFilter
{
    public function execute(
        PurifierFilter $purifierFilter,
        ?Product $newProduct,
        $replacedAt,
        ?int $replacementMonths = null,
        ?int $inputTds = null,
        ?int $outputTds = null,
        ?int $technicianId = null,
        ?int $serviceOrderId = null,
        $unitCost = 0,
        $unitPrice = 0,
        ?string $note = null
    ): FilterReplacementHistory {
        return DB::transaction(function () use (
            $purifierFilter, $newProduct, $replacedAt, $replacementMonths,
            $inputTds, $outputTds, $technicianId, $serviceOrderId,
            $unitCost, $unitPrice, $note
        ) {
            $purifierFilter->refresh();
            $oldProductId = $purifierFilter->product_id;
            $oldFilterName = $purifierFilter->filter_name;
            $replacementMonths = $replacementMonths
                ?? ($newProduct ? $newProduct->replacement_months : null)
                ?? $purifierFilter->replacement_months;

            $replacementDateTime = CarbonImmutable::parse($replacedAt);
            $nextReplaceAt = $replacementMonths
                ? $replacementDateTime->addMonths((int) $replacementMonths)->toDateString()
                : null;
            $newFilterName = $newProduct ? $newProduct->name : $oldFilterName;

            $history = FilterReplacementHistory::create([
                'purifier_filter_id' => $purifierFilter->id,
                'purifier_id' => $purifierFilter->purifier_id,
                'old_product_id' => $oldProductId,
                'new_product_id' => $newProduct ? $newProduct->id : null,
                'service_order_id' => $serviceOrderId,
                'technician_id' => $technicianId,
                'replaced_at' => $replacementDateTime,
                'replacement_months' => $replacementMonths,
                'next_replace_at' => $nextReplaceAt,
                'input_tds' => $inputTds,
                'output_tds' => $outputTds,
                'old_filter_name' => $oldFilterName,
                'new_filter_name' => $newFilterName,
                'quantity' => 1,
                'unit_cost' => $unitCost,
                'unit_price' => $unitPrice,
                'note' => $note,
            ]);

            $purifierFilter->update([
                'product_id' => $newProduct ? $newProduct->id : null,
                'filter_name' => $newFilterName,
                'installed_at' => $replacementDateTime->toDateString(),
                'last_replace_at' => $replacementDateTime->toDateString(),
                'replacement_months' => $replacementMonths,
                'next_replace_at' => $nextReplaceAt,
                'status' => PurifierFilterStatus::ACTIVE,
            ]);

            $purifierUpdates = ['last_service_at' => $replacementDateTime];
            if ($inputTds !== null) {
                $purifierUpdates['water_input_tds'] = $inputTds;
            }
            if ($outputTds !== null) {
                $purifierUpdates['water_output_tds'] = $outputTds;
            }
            $purifierFilter->waterPurifier()->update($purifierUpdates);

            return $history;
        });
    }
}
