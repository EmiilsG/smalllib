<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_index_page_loads(): void
    {
        Book::factory()->count(3)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
    }

    public function test_book_create_page_loads(): void
    {
        $response = $this->get(route('books.create'));

        $response->assertStatus(200);
    }

    public function test_book_can_be_stored(): void
    {
        $response = $this->post(route('books.store'), [
            'title' => 'Testa grāmata',
            'isbn' => '978-1-234-56789-99',
            'total_copies' => 3,
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['isbn' => '978-1-234-56789-99']);
    }

    public function test_book_cannot_be_stored_without_title(): void
    {
        $response = $this->post(route('books.store'), [
            'isbn' => '978-1-234-56789-98',
            'total_copies' => 1,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_book_show_page_loads(): void
    {
        $book = Book::factory()->create();

        $response = $this->get(route('books.show', $book));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_book_can_be_updated(): void
    {
        $book = Book::factory()->create();

        $response = $this->put(route('books.update', $book), [
            'title' => 'Atjaunots nosaukums',
            'isbn' => $book->isbn,
            'total_copies' => 5,
        ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', ['title' => 'Atjaunots nosaukums']);
    }

    public function test_book_can_be_deleted(): void
    {
        $book = Book::factory()->create();

        $response = $this->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
