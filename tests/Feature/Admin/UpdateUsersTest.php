<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    private $profession;

    protected $defaultData = [
        'name' => 'Pepe',
        'email' => 'pepe@mail.es',
        'password' => '123456',
        'bio' => "Programador de Laravel y VueJS",
        'twitter' => 'https://twitter.com/pepe',
        'profession_id' => '',
        'role' => 'user',
    ];

    /** @test */
    function it_loads_the_edit_user_page()
    {
        $user = factory(User::class)->create();

        $this->get('usuarios/'.$user->id.'/editar')
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar usuario')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    function it_updates_a_user()
    {
        $user = factory(User::class)->create();

        $this->put('usuarios/'.$user->id, $this->withData())
            ->assertRedirect('usuarios/'.$user->id);

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);
    }

    /** @test */
    function the_name_is_required()
    {
        $this->handleValidationExceptions();
        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'name' => ''
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', [
            'email' => 'pepe@mail.es',
        ]);
    }

    /** @test */
    function the_email_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email' => ''
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['name' => 'Pepe']);
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email' => 'correo-no-valido'
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', ['name' => 'Pepe']);
    }

    /** @test */
    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();

        factory(User::class)->create([
            'email' => 'existing-email@mail.es',
        ]);

        $user = factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData([
                'email' => 'existing-email@mail.es'
            ]))->assertRedirect('usuarios/'.$user->id.'/editar')
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    function the_password_is_optional()
    {
        $this->handleValidationExceptions();

        $oldPassword = 'Clave_anterior';
        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword),
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, [
                'name' => 'Pepe',
                'email' => 'pepe@mail.es',
                'password' => '',
            ])->assertRedirect('usuarios/'.$user->id);

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => $oldPassword,
        ]);
    }

    /** @test */
    function the_user_email_can_stay_the_same()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/'.$user->id.'/editar')
            ->put('usuarios/'.$user->id, $this->withData())
            ->assertRedirect('usuarios/'.$user->id);

        $this->assertDatabaseHas('users', [
            'name' => 'Pepe',
            'email' => 'pepe@mail.es'
        ]);
    }
}
