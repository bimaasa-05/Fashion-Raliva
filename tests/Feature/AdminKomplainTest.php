<?php
namespace Tests\Feature;
use App\Models\User;
use Tests\TestCase;
class AdminKomplainTest extends TestCase
{
    public function test_komplain_page_renders(): void
    {
        $this->withoutExceptionHandling();
        $admin = User::where('email', 'admin@raliva.test')->firstOrFail();
        $this->post('/login', ['email' => $admin->email, 'password' => 'password']);
        $this->get('/admin/komplain')->assertOk();
    }
}