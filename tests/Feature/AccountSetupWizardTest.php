<?php

use App\Filament\App\Pages\AccountSetupWizard;
use App\Models\IntegrationConnection;
use App\Models\TeamOnboarding;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Liberu\Foundation\Integrations\Support\CredentialVault;
use Liberu\Foundation\Organizations\Models\Team;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('saves team setup and encrypts the selected provider key', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $user->forceFill(['current_team_id' => $team->id])->save();

    $this->actingAs($user);
    Filament::setCurrentPanel(Filament::getPanel('app'));

    Livewire::test(AccountSetupWizard::class)
        ->set('data', [
            'name' => 'Ada Lovelace',
            'team_name' => 'Analytical Engines',
            'ai_provider' => 'openai',
            'ai_api_key' => 'sk-test-key',
        ])
        ->call('save')
        ->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('Ada Lovelace')
        ->and($team->fresh()->name)->toBe('Analytical Engines')
        ->and(TeamOnboarding::query()->where('team_id', $team->id)->value('completed_at'))->not->toBeNull();

    $connection = IntegrationConnection::query()->where('scope_id', (string) $team->id)->firstOrFail();

    expect($connection->credentials)->not->toContain('sk-test-key')
        ->and(app(CredentialVault::class)->open($connection->credentials))->toBe(['api_key' => 'sk-test-key']);
});
