<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'first_name' => 'Joel',
        ]);

        factory(User::class)->create([
            'first_name' => 'Ellie',
        ]);

        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee(trans('users.title.index'))
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test */
    function it_shows_a_default_message_if_the_users_list_is_empty()
    {
        $this->get('usuarios?empty')
            ->assertStatus(200)
            ->assertSee(trans('users.title.index'))
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    function it_paginates_the_users()
    {
        factory(User::class)->create([
            'first_name' => 'Tercer usuario',
            'created_at' => now()->subDays(5),
        ]);

        factory(User::class)->times(12)->create([
            'created_at' => now()->subDays(4),
        ]);

        factory(User::class)->create([
            'first_name' => 'Decimoseptimo usuario',
            'created_at' => now()->subDays(2),
        ]);

        factory(User::class)->create([
            'first_name' => 'Segundo usuario',
            'created_at' => now()->subDays(6),
        ]);

        factory(User::class)->create([
            'first_name' => 'Primer usuario',
            'created_at' => now()->subWeek(),
        ]);

        factory(User::class)->create([
            'first_name' => 'Decimosexto usuario',
            'created_at' => now()->subDays(3),
        ]);

        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Decimoseptimo usuario',
                'Decimosexto usuario',
                'Tercer usuario',
            ])
            ->assertDontSee('Segundo usuario')
            ->assertDontSee('Primer usuario');

        $this->get('usuarios?page=2')
            ->assertSeeInOrder([
                'Segundo usuario',
                'Primer usuario',
            ])
            ->assertDontSee('Tercer usuario');
    }

    /** @test */
    function users_are_ordered_by_name()
    {
        factory(User::class)->create(['last_name' => 'John Doe']);
        factory(User::class)->create(['last_name' => 'Richard Roe']);
        factory(User::class)->create(['last_name' => 'Jane Doe']);

        $this->get('usuarios?order=last_name&direction=asc')
            ->assertSeeInOrder([
                'Jane Doe',
                'John Doe',
                'Richard Roe'
            ]);

        $this->get('usuarios?order=last_name&direction=desc')
            ->assertSeeInOrder([
                'Richard Roe',
                'John Doe',
                'Jane Doe',
            ]);
    }

    /** @test */
    function users_are_ordered_by_email()
    {
        factory(User::class)->create(['email' => 'john.doe@example.com']);
        factory(User::class)->create(['email' => 'richard.roe@example.com']);
        factory(User::class)->create(['email' => 'jane.doe@example.com']);

        $this->get('usuarios?order=email&direction=asc')
            ->assertSeeInOrder([
                'jane.doe@example.com',
                'john.doe@example.com',
                'richard.roe@example.com',
            ]);

        $this->get('usuarios?order=email&direction=desc')
            ->assertSeeInOrder([
                'richard.roe@example.com',
                'john.doe@example.com',
                'jane.doe@example.com',
            ]);
    }

    /** @test */
    function users_are_ordered_by_registration_date()
    {
        factory(User::class)->create(['first_name' => 'John Doe', 'created_at' => now()->subDays(2)]);
        factory(User::class)->create(['first_name' => 'Richard Roe', 'created_at' => now()->subDays(3)]);
        factory(User::class)->create(['first_name' => 'Jane Doe', 'created_at' => now()->subDays(5)]);

        $this->get('usuarios?order=created_at&direction=asc')
            ->assertSeeInOrder([
                'Jane Doe',
                'Richard Roe',
                'John Doe',
            ]);

        $this->get('usuarios?order=created_at&direction=desc')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);
    }

    /** @test */
    function invalid_order_query_data_is_ignored_and_the_default_order_is_used_instead()
    {
        factory(User::class)->create(['first_name' => 'John Doe', 'created_at' => now()->subDays(2)]);
        factory(User::class)->create(['first_name' => 'Jane Doe', 'created_at' => now()->subDays(5)]);
        factory(User::class)->create(['first_name' => 'Richard Roe', 'created_at' => now()->subDays(3)]);

        $this->get('usuarios?order=invalid_order&direction=asc')
            ->assertOk()
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);

        $this->get('usuarios?order=invalid_order&direction=desc')
            ->assertOk()
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);
    }

    /** @test */
    function invalid_direction_query_data_is_ignored_and_the_default_direction_is_used_instead()
    {
        factory(User::class)->create(['first_name' => 'John', 'last_name' => 'Foe', 'created_at' => now()->subDays(2)]);
        factory(User::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe', 'created_at' => now()->subDays(5)]);
        factory(User::class)->create([
            'first_name' => 'Richard', 'last_name' => 'Roe', 'created_at' => now()->subDays(3)
        ]);

        $this->get('usuarios?order=last_name&direction=down')
            ->assertOk()
            ->assertSeeInOrder([
                'Jane Doe',
                'John Foe',
                'Richard Roe',
            ]);
    }
}
