<?php

namespace Tests\Feature;

use App\User;
use App\ToDo;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ToDoCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_to_do_item()
    {
        Auth::login($user = factory(User::class)->create());

        $new_todo = factory(ToDo::class)->make([
            'complete' => 0,
        ]);

        $response = $this->json('post', route('app.todo-create'), $new_todo->toArray());

        $this->assertDatabaseHas('to_dos', [
            'user_id' => $user->id,
            'title' => $new_todo->title,
            'complete' => 0
        ]);

        $todo = ToDo::find(1);

        $response->assertJson([
            'status' => 'success',
            'next_id' => $todo->id + 1,
        ]);
    }

    /** @test */
    public function user_can_fetch_all_to_dos()
    {
        Auth::login($user = factory(User::class)->create());

        $todo1 = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);
        $todo2 = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);
        factory(ToDo::class)->create(['complete' => 0]);

        $this->json('get', route('app.todo-fetch'))
            ->assertJson([
                'status' => 'success',
                'data' => [
                    $todo1->toArray(),
                    $todo2->toArray(),
                ]
            ]);
    }

    /** @test */
    public function user_can_fetch_incomplete_to_dos()
    {
        Auth::login($user = factory(User::class)->create());

        $todo1 = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);
        factory(ToDo::class, 3)->create(['user_id' => $user->id, 'complete' => 1]);

        $this->json('get', route('app.todo-fetch') . '?complete')
            ->assertJson([
                'status' => 'success',
                'data' => [
                    $todo1->toArray(),
                ]
            ]);
    }

    /** @test */
    public function user_can_update_to_do_name()
    {
        Auth::login($user = factory(User::class)->create());

        $todo = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);

        $this->json('patch', route('app.todo-update-title', $todo->id), [
            'title' => 'New Title',
        ])
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('to_dos', [
            'id' => $todo->id,
            'user_id' => $user->id,
            'title' => 'New Title',
            'complete' => $todo->complete
        ]);
    }

    /** @test */
    public function user_can_mark_to_do_as_complete_and_incomplete()
    {
        Auth::login($user = factory(User::class)->create());

        $todo = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);

        $this->json('patch', route('app.todo-update-status', $todo->id))
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('to_dos', [
            'id' => $todo->id,
            'user_id' => $user->id,
            'title' => $todo->title,
            'complete' => '1',
        ]);

        $this->json('patch', route('app.todo-update-status', $todo->id))
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('to_dos', [
            'id' => $todo->id,
            'user_id' => $user->id,
            'title' => $todo->title,
            'complete' => '0',
        ]);
    }

    /** @test */
    public function user_can_delete_single_to_do()
    {
        Auth::login($user = factory(User::class)->create());

        $todo = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);

        $this->json('delete', route('app.todo-destroy-single', $todo->id), [
            'id' => $todo->id,
        ])
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseMissing('to_dos', [
            'id' => $todo->id,
            'user_id' => $user->id,
            'title' => $todo->title,
            'complete' => $todo->complete,
        ]);
    }

    /** @test */
    public function user_can_delete_all_completed_to_dos()
    {
        Auth::login($user = factory(User::class)->create());

        $todo = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 1]);
        $todo2 = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 1]);
        $todo3 = factory(ToDo::class)->create(['user_id' => $user->id, 'complete' => 0]);

        $this->json('delete', route('app.todo-destroy-complete'))
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseMissing('to_dos', [
            'id' => $todo->id,
            'user_id' => $user->id,
            'title' => $todo->title,
            'complete' => $todo->complete,
        ]);

        $this->assertDatabaseMissing('to_dos', [
            'id' => $todo2->id,
            'user_id' => $user->id,
            'title' => $todo2->title,
            'complete' => $todo2->complete,
        ]);

        $this->assertDatabaseHas('to_dos', [
            'id' => $todo3->id,
            'user_id' => $user->id,
            'title' => $todo3->title,
            'complete' => $todo3->complete,
        ]);
    }
}
