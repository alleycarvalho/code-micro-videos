<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateBasic()
    {
        $genre = Genre::create([
            'name' => 'genre-1'
        ]);
        $genre->refresh();

        $this->assertEquals(36, strlen($genre->id));
        $this->assertEquals('genre-1', $genre->name);
        $this->assertTrue($genre->is_active);
    }

    public function testCreateIsActive()
    {
        $genre = Genre::create([
            'name' => 'genre-1',
            'is_active' => false
        ]);

        $this->assertFalse($genre->is_active);

        $genre = Genre::create([
            'name' => 'genre-1',
            'is_active' => true
        ]);

        $this->assertTrue($genre->is_active);
    }

    public function testList()
    {
        factory(Genre::class, 1)->create();
        $genres = Genre::all();

        $this->assertCount(1, $genres);

        $genreKeys = array_keys($genres->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKeys
        );
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'is_active' => false
        ]);

        $data = [
            'name' => 'genre-1-updated',
            'is_active' => true
        ];
        $genre->update($data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDeleteAndRestore()
    {
        $genre = factory(Genre::class)->create();
        $genre->delete();

        $this->assertNull(Genre::find($genre->id));

        $genre->restore();

        $this->assertNotNull(Genre::find($genre->id));
    }
}