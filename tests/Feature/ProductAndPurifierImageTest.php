<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\WaterPurifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAndPurifierImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_and_water_purifier_can_store_image_paths()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'image_path' => 'products/demo.jpg',
        ]);

        $customer = Customer::create([
            'customer_code' => 'CUS-IMAGE-001',
            'full_name' => 'Khách kiểm thử ảnh',
            'phone' => '0900000001',
            'customer_type' => 'individual',
            'status' => 'active',
        ]);

        $purifier = WaterPurifier::factory()->create([
            'customer_id' => $customer->id,
            'image_path' => 'water-purifiers/demo.jpg',
        ]);

        $this->assertSame('products/demo.jpg', $product->fresh()->image_path);
        $this->assertSame('water-purifiers/demo.jpg', $purifier->fresh()->image_path);
    }
}
