<?php

namespace App\Filament\App\Pages;

use App\Models\IntegrationConnection;
use App\Models\TeamOnboarding;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use JoelButcher\Socialstream\Providers;
use JoelButcher\Socialstream\Socialstream;
use Liberu\Foundation\Integrations\Support\CredentialVault;
use Liberu\Foundation\Organizations\Models\Team;

/**
 * @property-read Schema $form
 */
class AccountSetupWizard extends Page
{
    protected string $view = 'filament.app.pages.account-setup-wizard';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static string|\UnitEnum|null $navigationGroup = 'Account';

    protected static ?string $navigationLabel = 'Account setup';

    protected static ?int $navigationSort = -10;

    protected static ?string $title = 'Set up your account';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public ?Team $team = null;

    /** @var list<string> */
    public array $connectedProviders = [];

    public function mount(): void
    {
        $user = auth()->user();
        $tenant = Filament::getTenant();
        $team = $tenant instanceof Team
            ? $tenant
            : ($user !== null ? ($user->currentTeam ?? $user->latestTeam) : null);

        abort_unless($user !== null && $team instanceof Team && $user->belongsToTeam($team), 404);

        $this->team = $team;
        $this->connectedProviders = $user->connectedAccounts()->pluck('provider')->all();

        $this->form->fill([
            'name' => $user->name,
            'team_name' => $team->getAttribute('name'),
            'ai_provider' => $this->existingConnection()?->getAttribute('provider'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Wizard::make([
                    Step::make('Your profile')
                        ->icon('heroicon-o-user')
                        ->description('Personalise your workspace.')
                        ->schema([
                            TextInput::make('name')
                                ->label('Your name')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Step::make('Team settings')
                        ->icon('heroicon-o-user-group')
                        ->description('Give your team a useful home.')
                        ->schema([
                            TextInput::make('team_name')
                                ->label('Team name')
                                ->required()
                                ->maxLength(255),
                            Select::make('ai_provider')
                                ->label('Default AI provider')
                                ->placeholder('Choose this later')
                                ->options([
                                    'openai' => 'OpenAI',
                                    'anthropic' => 'Anthropic',
                                ])
                                ->native(false),
                            TextInput::make('ai_api_key')
                                ->label('AI API key')
                                ->password()
                                ->revealable()
                                ->maxLength(2000)
                                ->helperText('Stored encrypted. Leave blank if your team will add a key later.'),
                        ]),
                    Step::make('Connect accounts')
                        ->icon('heroicon-o-link')
                        ->description('Connect an OAuth identity for convenient sign-in.')
                        ->schema([
                            Section::make('OAuth connections')
                                ->description('Connecting an account is optional. Provider application credentials are configured by the application administrator.')
                                ->schema([]),
                        ]),
                ]),
            ]);
    }

    public function save(): void
    {
        $user = auth()->user();
        abort_unless($user !== null && $this->team instanceof Team, 404);

        $data = $this->form->getState();

        DB::transaction(function () use ($data, $user): void {
            $user->forceFill(['name' => $data['name']])->save();
            $this->team->forceFill(['name' => $data['team_name']])->save();

            $provider = $data['ai_provider'] ?? null;
            $apiKey = trim((string) ($data['ai_api_key'] ?? ''));

            if ($provider && $apiKey !== '') {
                IntegrationConnection::updateOrCreate(
                    [
                        'scope_type' => Team::class,
                        'scope_id' => (string) $this->team->getKey(),
                        'provider' => $provider,
                    ],
                    [
                        'credentials' => app(CredentialVault::class)->seal(['api_key' => $apiKey]),
                        'capabilities' => ['text-generation'],
                        'status' => 'ready',
                        'last_tested_at' => null,
                    ],
                );
            }

            TeamOnboarding::updateOrCreate(
                ['team_id' => $this->team->getKey()],
                ['completed_at' => now()],
            );
        });

        Notification::make()
            ->success()
            ->title('Account setup complete')
            ->body('Your profile and team settings are ready. You can add or rotate credentials from Integrations at any time.')
            ->send();
    }

    public function oauthProviders(): array
    {
        return Socialstream::providers();
    }

    public function providerName(string $provider): string
    {
        return Providers::name($provider);
    }

    public function oauthUrl(string $provider): string
    {
        return route('oauth.redirect', ['provider' => $provider]);
    }

    public function isProviderConnected(string $provider): bool
    {
        return in_array($provider, $this->connectedProviders, true);
    }

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    private function existingConnection(): ?IntegrationConnection
    {
        if (! $this->team instanceof Team) {
            return null;
        }

        return IntegrationConnection::query()
            ->where('scope_type', Team::class)
            ->where('scope_id', (string) $this->team->getKey())
            ->whereIn('provider', ['openai', 'anthropic'])
            ->latest('updated_at')
            ->first();
    }
}
