<?php
namespace Tests\Feature;
use App\Models\User;
use Tests\TestCase;
class OwnerKomplainTest extends TestCase
{
    public function test_komplain(): void
    {
        $this->withoutExceptionHandling();
        $owner = User::where('email','owner@raliva.test')->firstOrFail();
        $this->post('/login', ['email'=>$owner->email,'password'=>'password']);
        $this->get('/owner/komplain');
    }
}
