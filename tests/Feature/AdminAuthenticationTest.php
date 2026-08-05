<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_administrator_can_log_in_and_session_id_is_regenerated(): void
    {
        $admin = $this->createAdmin();

        $this->get(route('admin.login'))->assertOk();
        $sessionIdBeforeLogin = app('session.store')->getId();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'CorrectPassword1!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $this->assertNotSame($sessionIdBeforeLogin, app('session.store')->getId());
    }

    public function test_login_rejects_an_incorrect_email_or_password(): void
    {
        $admin = $this->createAdmin();

        $response = $this->from(route('admin.login'))
            ->post(route('admin.login'), [
                'email' => $admin->email,
                'password' => 'WrongPassword1!',
            ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        $this->createAdmin();
        Cache::clear();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('admin.login'), [
                'email' => 'unknown@example.test',
                'password' => 'WrongPassword1!',
            ])
                ->assertStatus(302);
        }

        $response = $this->post(route('admin.login'), [
            'email' => 'unknown@example.test',
            'password' => 'WrongPassword1!',
        ]);

        $response->assertTooManyRequests();
        $this->assertGuest();
    }

    public function test_logout_invalidates_the_authenticated_session(): void
    {
        $admin = $this->createAdmin();

        $loginResponse = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'CorrectPassword1!',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $authenticatedSessionId = app('session.store')->getId();

        $logoutResponse = $this->post(route('admin.logout'));

        $logoutResponse->assertRedirect(route('admin.login'));
        $this->assertGuest();

        $this->assertNotSame($authenticatedSessionId, app('session.store')->getId());
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_every_non_login_admin_route_uses_auth_middleware(): void
    {
        $protectedRoutes = collect(app('router')->getRoutes()->getRoutes())
            ->filter(fn (Route $route): bool => str_starts_with($route->uri(), 'admin')
                && $route->uri() !== 'admin/login'
                && ! in_array('guest', $route->gatherMiddleware(), true));

        $this->assertNotEmpty($protectedRoutes);

        foreach ($protectedRoutes as $route) {
            $this->assertContains(
                'auth',
                $route->gatherMiddleware(),
                "{$route->methods()[0]} {$route->uri()} must require authentication."
            );
        }
    }

    public function test_guests_are_redirected_from_each_admin_section_index(): void
    {
        $sectionRoutes = [
            'admin.dashboard',
            'admin.inline-editing',
            'admin.pages.index',
            'admin.messages.index',
            'admin.media.index',
            'admin.news.index',
            'admin.careers.index',
            'admin.board.index',
            'admin.portfolio.index',
            'admin.settings.index',
            'admin.users.index',
        ];

        foreach ($sectionRoutes as $routeName) {
            $this->get(route($routeName))
                ->assertRedirect(route('admin.login'));
        }
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make('CorrectPassword1!'),
        ]);
    }
}
