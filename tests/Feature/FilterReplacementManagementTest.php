<?php

namespace Tests\Feature;

use App\Actions\PurifierFilters\CreatePurifierFilter;
use App\Actions\PurifierFilters\ReplacePurifierFilter;
use App\Enums\ProductType;
use App\Models\FilterReplacementHistory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PurifierFilter;
use App\Models\WaterPurifier;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FilterReplacementManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_creates_a_category_and_product(): void
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'product_type' => ProductType::FILTER,
        ]);

        $this->assertTrue($product->category->is($category));
        $this->assertTrue($category->products->contains($product));
        $this->assertSame(ProductType::FILTER, $product->product_type->value);
    }

    public function test_create_action_calculates_next_replacement_date(): void
    {
        $purifier = WaterPurifier::factory()->create();

        $filter = app(CreatePurifierFilter::class)->execute([
            'purifier_id' => $purifier->id,
            'filter_name' => 'PP 5 Micron',
            'installed_at' => '2026-08-24',
            'replacement_months' => 3,
        ]);

        $this->assertSame('2026-11-24', $filter->next_replace_at->toDateString());
    }

    public function test_replace_action_creates_history_and_updates_filter(): void
    {
        $filter = PurifierFilter::factory()->create(['replacement_months' => 3]);
        $oldProductId = $filter->product_id;
        $newProduct = Product::factory()->create(['replacement_months' => 6]);
        $replacedAt = CarbonImmutable::parse('2026-08-24 10:30:00');

        $history = app(ReplacePurifierFilter::class)->execute(
            $filter, $newProduct, $replacedAt, 4
        );
        $filter->refresh();

        $this->assertSame(1, FilterReplacementHistory::where('purifier_filter_id', $filter->id)->count());
        $this->assertSame($newProduct->id, $filter->product_id);
        $this->assertSame('2026-08-24', $filter->last_replace_at->toDateString());
        $this->assertSame('2026-12-24', $filter->next_replace_at->toDateString());
        $this->assertSame($oldProductId, $history->old_product_id);
        $this->assertSame($newProduct->id, $history->new_product_id);
        $this->assertSame('2026-08-24 10:30:00', $replacedAt->toDateTimeString());
    }

    public function test_replace_action_uses_new_product_replacement_months(): void
    {
        $filter = PurifierFilter::factory()->create(['replacement_months' => 3]);
        $newProduct = Product::factory()->create(['replacement_months' => 6]);

        app(ReplacePurifierFilter::class)->execute($filter, $newProduct, '2026-08-24');

        $this->assertSame(6, $filter->refresh()->replacement_months);
        $this->assertSame('2027-02-24', $filter->next_replace_at->toDateString());
    }

    public function test_manual_replacement_months_override_product_value(): void
    {
        $filter = PurifierFilter::factory()->create(['replacement_months' => 3]);
        $newProduct = Product::factory()->create(['replacement_months' => 6]);

        app(ReplacePurifierFilter::class)->execute($filter, $newProduct, '2026-08-24', 9);

        $this->assertSame(9, $filter->refresh()->replacement_months);
        $this->assertSame('2027-05-24', $filter->next_replace_at->toDateString());
    }

    public function test_next_replacement_date_is_null_without_replacement_months(): void
    {
        $filter = PurifierFilter::factory()->create([
            'replacement_months' => null,
            'next_replace_at' => null,
        ]);

        $history = app(ReplacePurifierFilter::class)->execute($filter, null, '2026-08-24');

        $this->assertNull($filter->refresh()->next_replace_at);
        $this->assertNull($history->next_replace_at);
    }

    public function test_soft_deleting_product_does_not_remove_history(): void
    {
        $filter = PurifierFilter::factory()->create();
        $newProduct = Product::factory()->create(['replacement_months' => 6]);
        $history = app(ReplacePurifierFilter::class)->execute($filter, $newProduct, '2026-08-24');

        $newProduct->delete();

        $this->assertDatabaseHas('filter_replacement_histories', ['id' => $history->id]);
        $this->assertTrue($history->fresh()->newProduct->trashed());
    }

    public function test_soft_deleting_filter_does_not_remove_history(): void
    {
        $filter = PurifierFilter::factory()->create();
        $history = app(ReplacePurifierFilter::class)->execute($filter, null, '2026-08-24');

        $filter->delete();

        $this->assertDatabaseHas('filter_replacement_histories', [
            'id' => $history->id,
            'purifier_filter_id' => $filter->id,
        ]);
    }
}
