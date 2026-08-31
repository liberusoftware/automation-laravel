<x-filament-panels::page>
    <div class="mx-auto w-full max-w-4xl space-y-6">
        <div class="rounded-2xl border border-primary-200 bg-primary-50 p-6 dark:border-primary-900 dark:bg-primary-950/40">
            <p class="text-sm font-medium text-primary-700 dark:text-primary-300">A few useful details, then you’re ready</p>
            <h2 class="mt-1 text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">Make this workspace yours</h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-600 dark:text-gray-300">Set your profile, name your team, and add the optional credentials your automations need. You can safely change any of this later.</p>
        </div>

        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" icon="heroicon-m-check-circle">
                    Finish setup
                </x-filament::button>
            </div>
        </form>

        <section aria-labelledby="oauth-heading" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 id="oauth-heading" class="text-base font-semibold text-gray-950 dark:text-white">Connect an OAuth account</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Use an existing provider account to sign in faster. This does not change your team permissions.</p>
                </div>
                <x-heroicon-o-link class="h-5 w-5 text-gray-400" />
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach ($this->oauthProviders() as $provider)
                    <a wire:key="oauth-{{ $provider['id'] }}" href="{{ $this->oauthUrl($provider['id']) }}" class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 transition hover:border-primary-400 hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/5">
                        <span class="flex items-center gap-3 text-sm font-medium text-gray-900 dark:text-white">
                            <x-socialstream-icons.provider-icon :provider="$provider['id']" class="h-5 w-5" />
                            {{ $this->providerName($provider['id']) }}
                        </span>
                        @if ($this->isProviderConnected($provider['id']))
                            <span class="text-xs font-medium text-success-600 dark:text-success-400">Connected</span>
                        @else
                            <span class="text-xs text-gray-500">Connect</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-filament-panels::page>
