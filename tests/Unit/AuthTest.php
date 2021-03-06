<?php

namespace Tests\Unit;

use App\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_collector_cannot_access_admin_dashboard()
    {
        $user = create(Admin::class);
        $this->userSignIn($user);

        $this->get(route('admin.dashboard'))
            ->assertStatus(302)
            ->assertRedirect(route('admin.login'));
    }

    /** @test */
    function an_admin_can_access_admin_dashboard()
    {
        $admin = create(Admin::class, ['access_level' => 2]);
        $this->adminSignIn($admin);

        $this->get(route('admin.dashboard'))
            ->assertStatus(200);
    }
}