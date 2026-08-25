<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationRedirectTest extends TestCase
{
    public function test_guest_admin_request_redirects_to_filament_login()
    {
        $this->get('/admin')
            ->assertRedirect(route('filament.auth.login'));
    }

    public function test_guest_api_request_returns_unauthorized_instead_of_redirecting()
    {
        $this->get('/api/user')
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
