<?php

namespace Tests;

use App\Admin;
use App\Admin;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        Schema::enableForeignKeyConstraints();
    }

    protected function adminSignIn($admin = null)
    {
        $admin = $admin ?: create(Admin::class);
        $this->ActingAs($admin, 'admin');

        return $this;
    }

    protected function userSignIn($user = null)
    {
        $user = $user ?: create(Admin::class);
        $this->ActingAs($user);

        return $this;
    }
}
