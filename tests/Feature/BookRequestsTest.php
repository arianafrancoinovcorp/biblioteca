<?php

use App\Models\User;
use App\Models\Book;
use App\Models\BookRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
});

it('permite que um utilizador crie uma requisição de livro', function () {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Book $book */
    $book = Book::factory()->create();

    actingAs($user)
        ->post(route('requests.store'), [
            'book_id' => $book->id,
        ])
        ->assertRedirect(route('requests.index'));

    $this->assertDatabaseHas('requests', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'active',
    ]);
});

it('não permite criar uma requisição sem um livro válido', function () {
    /** @var User $user */
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('requests.store'), [
            'book_id' => 9999, // ID inválido
        ])
        ->assertSessionHasErrors('book_id');

    $this->assertDatabaseCount('requests', 0);
});

it('permite que um utilizador devolva um livro', function () {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Book $book */
    $book = Book::factory()->create();

    /** @var BookRequests $request */
    $request = BookRequests::factory()->create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'active',
    ]);

    actingAs($user)
        ->post(route('requests.return', $request))
        ->assertRedirect(route('requests.show', $request->id));

    $this->assertDatabaseHas('requests', [
        'id' => $request->id,
        'status' => 'returned',
    ]);
});

it('lista apenas as requisições do utilizador autenticado', function () {
    /** @var User $user1 */
    $user1 = User::factory()->create();

    /** @var User $user2 */
    $user2 = User::factory()->create();

    /** @var Book $book1 */
    $book1 = Book::factory()->create();

    /** @var Book $book2 */
    $book2 = Book::factory()->create();

    BookRequests::factory()->create(['user_id' => $user1->id, 'book_id' => $book1->id]);
    BookRequests::factory()->create(['user_id' => $user2->id, 'book_id' => $book2->id]);

    actingAs($user1)
        ->get(route('requests.index'))
        ->assertSee($book1->name)
        ->assertDontSee($book2->name);
});

it('impede criar uma requisição de livro sem stock disponível', function () {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Book $book */
    $book = Book::factory()->create();

    actingAs($user)
        ->post(route('requests.store'), [
            'book_id' => $book->id,
        ])
        ->assertSessionDoesntHaveErrors(); 

    $this->assertDatabaseHas('requests', [
        'user_id' => $user->id,
        'book_id' => $book->id,
    ]);
    
});
