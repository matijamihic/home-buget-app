<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class IncomeControllerTest extends TestCase
{
    public function test_authentification_is_required()
    {
        // test that authentification is required for income routes
        $this->get('/api/incomes')->assertStatus(401);
        $this->get('/api/incomes/1')->assertStatus(401);
        $this->post('/api/incomes')->assertStatus(401);
        $this->put('/api/incomes/1')->assertStatus(401);
        $this->delete('/api/incomes/1')->assertStatus(401);
    }
}