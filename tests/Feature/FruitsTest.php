<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use App\Fruit;

class FruitsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testit_praises_the_fruits(){
        $this->seed('FruitsTableSeeder');
        $this->get('/api/fruits')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name', 'color', 'weight', 'delicious'
                    ]
                ]
            ]);
    }

    public function testSingleFruit(){
        $this->seed('FruitsTableSeeder');
        $this->get('/api/fruit/1')
            ->assertJson([
                'data' => [
                    'id'        => 1,
                    'name'      => "Orange",
                    'color'     => "Orange",
                    'weight'    => "100 grams",
                    'delicious' => 1
                ]
            ]);
    }

    /**
     * @test
     *
     * Test: GET /api/authenticate.
     */
    public function testItAuthenticateSingleUser()
    {
        $user = factory(\App\User::class)->create(['password' => bcrypt('foo')]);

        $this->post('/api/authenticate', ['email' => $user->email, 'password' => 'foo'])
            ->assertJsonStructure(['token']);
    }

    /**
     * Return request headers needed to interact with the API.
     *
     * @return Array array of headers.
     */
    protected function headers($user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }

    /**
     * @test
     *
     * Test: POST /api/fruits.
     */
    public function testSaveFruit()
    {
        $user = factory(\App\User::class)->create(['password' => bcrypt('foo')]);

        $fruit = ['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => 1];

        $this->post('/api/fruits', $fruit, $this->headers($user))
            ->assertStatus(201);
    }

    /**
     * @test
     *
     * Test: POST /api/fruits.
     */
    public function test401WhenNotAuthorized()
    {
        $fruit = Fruit::create(['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => 1])->toArray();

        $this->post('/api/fruits', $fruit)
            ->assertStatus(401);
    }

    /**
     * @test
     *
     * Test: POST /api/fruits.
     */
    public function testit_422_when_validation_fails()
    {
        $user = factory(\App\User::class)->create(['password' => bcrypt('foo')]);

        $fruit = ['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => 1];

        $this->post('/api/fruits', $fruit, $this->headers($user))
            ->assertStatus(201);

        $this->post('/api/fruits', $fruit, $this->headers($user))
            ->assertStatus(422);
    }

    /**
     * @test
     *
     * Test: DELETE /api/fruits/$id.
     */
    public function testit_deletes_a_fruit()
    {
        $user = factory(\App\User::class)->create(['password' => bcrypt('foo')]);

        $fruit = Fruit::create(['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => true]);

        $this->delete('/api/fruits/' . $fruit->id, [], $this->headers($user))
            ->assertStatus(204);
    }
}
