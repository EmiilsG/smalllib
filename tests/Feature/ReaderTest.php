<?php

namespace Tests\Feature;

use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_reader_index_page_loads(): void
    {
        Reader::factory()->count(3)->create();

        $response = $this->get(route('readers.index'));

        $response->assertStatus(200);
    }

    public function test_reader_create_page_loads(): void
    {
        $response = $this->get(route('readers.create'));

        $response->assertStatus(200);
    }

    public function test_reader_can_be_stored(): void
    {
        $response = $this->post(route('readers.store'), [
            'name' => 'Jānis Tests',
            'email' => 'janis.tests@example.com',
        ]);

        $response->assertRedirect(route('readers.index'));
        $this->assertDatabaseHas('readers', ['email' => 'janis.tests@example.com']);
    }

    public function test_reader_show_page_loads(): void
    {
        $reader = Reader::factory()->create();

        $response = $this->get(route('readers.show', $reader));

        $response->assertStatus(200);
        $response->assertSee($reader->name);
    }

    public function test_reader_can_be_updated(): void
    {
        $reader = Reader::factory()->create();

        $response = $this->put(route('readers.update', $reader), [
            'name' => 'Atjaunots Vārds',
            'email' => $reader->email,
        ]);

        $response->assertRedirect(route('readers.index'));
        $this->assertDatabaseHas('readers', ['name' => 'Atjaunots Vārds']);
    }

    public function test_reader_can_be_deleted(): void
    {
        $reader = Reader::factory()->create();

        $response = $this->delete(route('readers.destroy', $reader));

        $response->assertRedirect(route('readers.index'));
        $this->assertDatabaseMissing('readers', ['id' => $reader->id]);
    }
}
