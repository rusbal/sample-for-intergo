<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_registration_requires_email_confirmation()
    {
        $this->visit('/register')
            ->type('John Doe', 'name')
            ->type('john@example.com', 'email')
            ->type('the-password', 'password')
            ->type('the-password', 'password_confirmation')
            ->press('Register');

        $this->see('Please confirm your email address.');
    }
}
