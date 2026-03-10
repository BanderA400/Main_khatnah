<?php

namespace Tests\Feature\Filament;

use App\Filament\Control\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ControlUserDeletionSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_delete_is_blocked_if_selection_contains_current_user(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);
        $otherUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($admin);
        Filament::setCurrentPanel(Filament::getPanel('control'));

        Livewire::test(ListUsers::class)
            ->callTableBulkAction('delete', [$admin, $otherUser])
            ->assertTableBulkActionHalted('delete');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $otherUser->id,
        ]);
    }

    public function test_bulk_delete_of_other_admins_is_allowed_when_one_admin_remains(): void
    {
        $currentAdmin = User::factory()->create([
            'is_admin' => true,
        ]);
        $otherAdminA = User::factory()->create([
            'is_admin' => true,
        ]);
        $otherAdminB = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($currentAdmin);
        Filament::setCurrentPanel(Filament::getPanel('control'));

        Livewire::test(ListUsers::class)
            ->callTableBulkAction('delete', [$otherAdminA, $otherAdminB]);

        $this->assertDatabaseHas('users', [
            'id' => $currentAdmin->id,
            'is_admin' => true,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $otherAdminA->id,
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $otherAdminB->id,
        ]);
    }
}
