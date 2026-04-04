<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create([
            'name'        => 'Test Category',
            'description' => null,
        ]);
    }

    private function productData(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Test Product',
            'price'       => 99.99,
            'category_id' => $this->category()->id,
            'description' => 'A test product.',
            'is_active'   => true,
        ], $overrides);
    }

    public function test_can_list_products(): void
    {
        $category = $this->category();
        Product::create([...$this->productData(), 'category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_can_create_product(): void
    {
        $response = $this->postJson('/api/products', $this->productData());

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Test Product');

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_create_product_fails_validation(): void
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_can_show_product(): void
    {
        $product = Product::create($this->productData());

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_show_product_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/products/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_can_update_product(): void
    {
        $product = Product::create($this->productData());

        $response = $this->putJson("/api/products/{$product->id}", [
            'name'  => 'Updated Product',
            'price' => 149.99,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Updated Product');
    }

    public function test_can_delete_product(): void
    {
        $product = Product::create($this->productData());

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
