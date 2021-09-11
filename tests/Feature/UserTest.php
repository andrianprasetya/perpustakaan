<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
        public function testUserSearchMustFail()
    {
        $dataSearch = [];
        $dataSearch['search'] = 'TestUser';
        $dataSearch['limit'] = 100;
        $buildParams = http_build_query($dataSearch, PHP_QUERY_RFC1738);
        $url = route('user.index');
        $fullUrl = "$url?$buildParams";
        $response = $this->getJson($fullUrl);

        $response
            ->assertStatus(200);
    }

    public function testCreateMustOK()
    {

        $fullUrl = route('user.store');
        $response = $this->postJson($fullUrl,
            [
                'name' => 'Andrian Prasetya',
                'email' => 'andrianprasetya223@gmail.com',
                'password' => 'test1234'
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.item.name', 'Andrian Prasetya')
            ->assertJsonPath('data.item.email', 'andrianprasetya223@gmail.com');
    }

    public function testUserSearchMustOK()
    {
        $dataSearch = [];
        $dataSearch['search'] = 'andrianprasetya223@gmail.com';
        $dataSearch['limit'] = 100;
        $buildParams = http_build_query($dataSearch, PHP_QUERY_RFC1738);
        $url = route('user.index');
        $fullUrl = "$url?$buildParams";
        $response = $this->getJson($fullUrl);

        $response
            ->assertStatus(200)
            ->assertJsonPath('page_info.total', 1);
    }



    public function testDeleteUserMustOk()
    {
        $User = User::where('email', '=', 'andrianprasetya223@gmail.com')->first();
        $response = $this->postJson(route('user.delete'), [
            'id' => $User->id,
        ]);

        $response->assertStatus(200);
    }
}
