<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\AuthorizeAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AuthorizeAdminTest extends TestCase
{
    use DatabaseMigrations;

    private Request $request;
    private AuthorizeAdmin $middleware;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->request = Request::create('/');
        $this->middleware = new AuthorizeAdmin();
    }

    public function test_should_not_pass_when_user_role_is_not_admin()
    {
        $nonAdminUser = User::factory()->create();
        Auth::login($nonAdminUser);

        $this->expectException(HttpException::class);
        $this->middleware->handle($this->request, function () {
            return new Response();
        });
    }

    public function test_should_pass_when_user_role_is_admin()
    {
        $adminUser = User::factory()->create([
            'name' => 'admin',
            'surname' => 'admin',
            'password' => 'password',
            'role' => 'ROLE_ADMIN'
        ]);

        Auth::login($adminUser);

        $response = new Response();
        $result = $this->middleware->handle($this->request, function () use ($response) {
            return $response;
        });

        $this->assertSame($response, $result);
    }
}
