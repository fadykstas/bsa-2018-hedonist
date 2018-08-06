<?php

namespace Tests\Feature\Repository\UserTastesTest;


use Hedonist\Entities\User\User;
use Hedonist\Entities\User\UserTaste;
use Hedonist\Repositories\User\UserRepository;
use Hedonist\Repositories\UserTaste\UserTasteRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTastesTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $userRepo;
    private $tasteRepo;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->userRepo = new UserRepository(app());
        $this->tasteRepo = new UserTasteRepository(app());
    }

    public function test_relation()
    {
        $taste = factory(UserTaste::class)->make();
        $this->user->tastes()->save($taste);

        $this->assertDatabaseHas(
            'users_user_tastes',
            ['user_id' => $this->user->id, 'user_taste_id' => $taste->id]
        );
    }

    public function test_set_taste()
    {
        $tastes = factory(UserTaste::class,3)->make();
        $tastes->each(function ($item) {
            $this->tasteRepo->save($item);
        });

        $this->assertEquals($tastes->count(), $this->tasteRepo->findAll()->count());
        $this->userRepo->setTastes($this->user,$tastes);
        $this->assertEquals($tastes->count(), $this->userRepo->getTastes($this->user)->count());
    }

    public function test_add_taste()
    {
        $taste = factory(UserTaste::class)->make();
        $this->user->tastes()->save($taste);
        $tasteToAdd = factory(UserTaste::class)->create();
        $this->userRepo->addTaste($this->user,$tasteToAdd);
        $this->assertEquals(2, $this->userRepo->getTastes($this->user)->count());
    }

    public function test_delete_taste()
    {
        $taste = factory(UserTaste::class,2)->make();
        $this->user->tastes()->saveMany($taste);
        $this->userRepo->deleteTaste($this->user,$taste[0]);
        $this->assertEquals(1, $this->userRepo->getTastes($this->user)->count());
    }
}