<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WelcomeUsersTest extends TestCase
{
    /** @test  */
    function it_welcomes_users_with_nickname()
    {
        $this->get('saludo/pepe/pepon')
            ->assertStatus(200)
            ->assertSee('Bienvenido Pepe, tu apodo es pepon');
    }

    /** @test  */
    function it_welcomes_users_without_nickname()
    {
        $this->get('saludo/pepe')
            ->assertStatus(200)
            ->assertSee('Bienvenido Pepe');
    }
}
