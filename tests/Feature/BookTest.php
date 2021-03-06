<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBookSearchMustOK()
    {
        $dataSearch = [];
        $dataSearch['search'] = 'TestBook';
        $dataSearch['limit'] = 100;
        $buildParams = http_build_query($dataSearch, PHP_QUERY_RFC1738);
        $url = route('book.index');
        $fullUrl = "$url?$buildParams";
        $response = $this->getJson($fullUrl);

        $response
            ->assertStatus(200)
            ->assertJsonPath('page_info.total', 1);
    }

    public function testUserSearchMustFail()
    {
        $dataSearch = [];
        $dataSearch['search'] = 'TestBook';
        $dataSearch['limit'] = 100;
        $buildParams = http_build_query($dataSearch, PHP_QUERY_RFC1738);
        $url = route('book.index');
        $fullUrl = "$url?$buildParams";
        $response = $this->getJson($fullUrl);

        $response
            ->assertStatus(200)
            ->assertJsonPath('page_info.total', 1);
    }

    public function testCreateMustOK()
    {

        $fullUrl = route('book.store');
        $response = $this->postJson($fullUrl,
            [
                'name' => 'BookCreate',
                'description' => 'ThisIsDescription',
                'is_active' => 1
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.item.name', 'BookCreate')
            ->assertJsonPath('data.item.description', 'ThisIsDescription')
            ->assertJsonPath('data.item.is_active', 1);
    }

    public function testDeleteBookMustOk()
    {
        $book = Book::where('name', '=', 'BookCreate')->first();
        $response = $this->postJson(route('book.delete'), [
            'id' => $book->id,
        ]);

        $response->assertStatus(200);
    }
}
