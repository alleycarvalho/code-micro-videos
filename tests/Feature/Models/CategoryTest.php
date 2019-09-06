<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateBasic()
    {
        $category = Category::create([
            'name' => 'category-1'
        ]);
        $category->refresh();

        $this->assertEquals(36, strlen($category->id));
        $this->assertEquals('category-1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);
    }

    public function testCreateDescription()
    {
        $category = Category::create([
            'name' => 'category-1',
            'description' => null
        ]);

        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'category-1',
            'description' => 'category-1 description'
        ]);

        $this->assertEquals('category-1 description', $category->description);
    }

    public function testCreateIsActive()
    {
        $category = Category::create([
            'name' => 'category-1',
            'is_active' => false
        ]);

        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'category-1',
            'is_active' => true
        ]);

        $this->assertTrue($category->is_active);
    }

    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();

        $this->assertCount(1, $categories);

        $categoryKeys = array_keys($categories->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKeys
        );
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'category-1 description',
            'is_active' => false
        ]);

        $data = [
            'name' => 'category-1-updated',
            'description' => 'category-1 updated',
            'is_active' => true
        ];
        $category->update($data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDeleteAndRestore()
    {
        $category = factory(Category::class)->create();
        $category->delete();

        $this->assertNull(Category::find($category->id));

        $category->restore();

        $this->assertNotNull(Category::find($category->id));
    }
}
