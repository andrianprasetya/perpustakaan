<?php

namespace Tests\Feature;

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

    public function testOutletSearchMustFail()
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
}
