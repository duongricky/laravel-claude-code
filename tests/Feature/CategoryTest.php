<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private function categoryData(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Test Category',
            'description' => 'A test category.',
        ], $overrides);
    }

    public function test_can_list_categories(): void
    {
        Category::create($this->categoryData());

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_can_create_category(): void
    {
        $response = $this->postJson('/api/categories', $this->categoryData());

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Test Category');

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_create_category_fails_validation(): void
    {
        $response = $this->postJson('/api/categories', []);

        $response->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_can_show_category(): void
    {
        $category = Category::create($this->categoryData());

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $category->id);
    }

    public function test_show_category_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/categories/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_can_update_category(): void
    {
        $category = Category::create($this->categoryData());

        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Updated Category');
    }

    public function test_can_delete_category(): void
    {
        $category = Category::create($this->categoryData());

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
