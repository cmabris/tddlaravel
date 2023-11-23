<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

    public function getValidData(array $custom = [])
    {
        $this->profession = factory(Profession::class)->create();

        return array_merge([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
            'bio' => "Programador de Laravel y VueJS",
            'twitter' => 'https://twitter.com/pepe',
            'profession_id' => '',
            'role' => 'user',
        ], $custom);
    }

    /** @test */
    function it_loads_the_new_user_page()
    {
        $profession = factory(Profession::class)->create();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get('usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo usuario')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            })
            ->assertViewHas('skills', function ($skills) use ($skillA, $skillB) {
                return $skills->contains($skillA && $skills->contains($skillB));
            });
    }

    /** @test */
    function it_creates_a_new_user()
    {
        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $skillC = factory(Skill::class)->create();

        $this->post('usuarios', $this->getValidData([
            'skills' => [$skillA->id, $skillB->id],
            'profession_id' => $profession->id,
        ]))
            ->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
            'role' => 'user'
        ]);

        $user = User::findByEmail('pepe@mail.es');

        $this->assertDatabaseHas('user_profiles', [
            'bio' => "Programador de Laravel y VueJS",
            'twitter' => 'https://twitter.com/pepe',
            'user_id' => User::findByEmail('pepe@mail.es')->id,
            'profession_id' => $profession->id,
        ]);

        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id,
        ]);
        $this->assertDatabaseHas('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id,
        ]);
        $this->assertDatabaseMissing('skill_user', [
            'user_id' => $user->id,
            'skill_id' => $skillC->id,
        ]);
    }

    /** @test */
    function the_name_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'name' => ''
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'name' => 'El campo nombre es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'email' => ''
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'El campo email es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_password_is_required()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'password' => ''
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'password' => 'El campo contraseña es obligatorio'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'email' => 'correo-no-valido',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();

        factory(User::class)->create([
            'email' => 'pepe@mail.es',
        ]);

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'email' => 'pepe@mail.es'
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors('email');

        $this->assertEquals(1, User::count());
    }

    /** @test */
    function the_twitter_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios', $this->getValidData([
            'twitter' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => "Programador de Laravel y VueJS",
            'twitter' => null,
            'user_id' => User::findByEmail('pepe@mail.es')->id
        ]);
    }

    /** @test */
    function the_profession_id_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios', $this->getValidData([
            'profession_id' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' => "Programador de Laravel y VueJS",
            'user_id' => User::findByEmail('pepe@mail.es')->id,
            'profession_id' => null,
        ]);
    }

    /** @test */
    function the_profession_id_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'profession_id' => '999'
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function only_not_deleted_professions_can_be_selected()
    {
        $this->handleValidationExceptions();

        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'profession_id' => $deletedProfession->id
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'skills' => 'PHP, JS'
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test  */
    function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'skills' => [$skillA->id, $skillB->id + 1]
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['skills']);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    function the_role_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'role' => null
            ]))
            ->assertRedirect('usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'pepe@mail.es',
            'role' => 'user',
        ]);
    }

    /** @test  */
    function the_role_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')
            ->post('usuarios', $this->getValidData([
                'role' => 'invalid-role'
            ]))
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['role']);

        $this->assertDatabaseEmpty('users');
    }
}
