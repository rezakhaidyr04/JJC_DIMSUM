<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_karyawan_cannot_access_owner_route(): void
    {
        $karyawan = User::factory()->karyawan()->create();

        $this->actingAs($karyawan)
            ->get('/void-requests')
            ->assertForbidden();
    }

    public function test_owner_can_access_owner_route(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->get('/void-requests')
            ->assertOk();
    }
}
