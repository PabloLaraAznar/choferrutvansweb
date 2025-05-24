<x-action-section>
    <x-slot name="title">
        {{ __('Autenticación en dos factores') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Añade una capa adicional de seguridad a tu cuenta utilizando la autenticación en dos factores.') }}
    </x-slot>

    <x-slot name="content">
        <div class="mt-3">
            <h3 class="text-lg font-weight-bold">
                @if ($this->enabled)
                    @if ($showingConfirmation)
                        {{ __('Termina de habilitar la autenticación en dos factores.') }}
                    @else
                        {{ __('Has habilitado la autenticación en dos factores.') }}
                    @endif
                @else
                    {{ __('No has habilitado la autenticación en dos factores.') }}
                @endif
            </h3>

            <div class="mt-3 max-w-xl text-muted">
                <p>
                    {{ __('Cuando la autenticación en dos factores esté habilitada, se te pedirá un código seguro y aleatorio durante el proceso de inicio de sesión. Puedes obtener este código usando una aplicación como Google Authenticator en tu teléfono.') }}
                </p>
            </div>

            @if ($this->enabled)
                @if ($showingQrCode)
                    <div class="mt-4 max-w-xl text-muted">
                        <p class="font-weight-bold">
                            @if ($showingConfirmation)
                                {{ __('Para finalizar, escanea el siguiente código QR con tu aplicación de autenticación o ingresa la clave de configuración y proporciona el código OTP generado.') }}
                            @else
                                {{ __('La autenticación en dos factores está activada. Escanea el siguiente código QR o ingresa la clave de configuración para completar el proceso.') }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 p-2 inline-block bg-white shadow rounded-lg">
                        {!! $this->user->twoFactorQrCodeSvg() !!}
                    </div>

                    <div class="mt-4 max-w-xl text-muted">
                        <p class="font-weight-bold">
                            {{ __('Clave de configuración') }}: {{ decrypt($this->user->two_factor_secret) }}
                        </p>
                    </div>

                    @if ($showingConfirmation)
                        <div class="mt-4">
                            <label for="code" class="form-label">{{ __('Código de verificación') }}</label>
                            <input id="code" type="text" name="code" class="form-control w-50" inputmode="numeric" autofocus autocomplete="one-time-code"
                                wire:model="code"
                                wire:keydown.enter="confirmTwoFactorAuthentication" />
                            <x-input-error for="code" class="mt-2" />
                        </div>
                    @endif
                @endif

                @if ($showingRecoveryCodes)
                    <div class="mt-4 max-w-xl text-muted">
                        <p class="font-weight-bold">
                            {{ __('Guarda estos códigos de recuperación en un lugar seguro, como un gestor de contraseñas. Los necesitarás si pierdes el acceso a tu dispositivo de autenticación.') }}
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-light rounded-lg">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                @endif
            @endif

            <div class="mt-5">
                @if (! $this->enabled)
                    <x-confirms-password wire:then="enableTwoFactorAuthentication">
                        <button type="button" class="btn btn-primary" wire:loading.attr="disabled">
                            {{ __('Habilitar autenticación') }}
                        </button>
                    </x-confirms-password>
                @else
                    @if ($showingRecoveryCodes)
                        <x-confirms-password wire:then="regenerateRecoveryCodes">
                            <button type="button" class="btn btn-secondary me-3">
                                {{ __('Regenerar códigos de recuperación') }}
                            </button>
                        </x-confirms-password>
                    @elseif ($showingConfirmation)
                        <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                            <button type="button" class="btn btn-primary me-3" wire:loading.attr="disabled">
                                {{ __('Confirmar') }}
                            </button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="showRecoveryCodes">
                            <button type="button" class="btn btn-secondary me-3">
                                {{ __('Mostrar códigos de recuperación') }}
                            </button>
                        </x-confirms-password>
                    @endif

                    @if ($showingConfirmation)
                        <x-confirms-password wire:then="disableTwoFactorAuthentication">
                            <button type="button" class="btn btn-danger" wire:loading.attr="disabled">
                                {{ __('Cancelar') }}
                            </button>
                        </x-confirms-password>
                    @else
                        <x-confirms-password wire:then="disableTwoFactorAuthentication">
                            <button type="button" class="btn btn-danger" wire:loading.attr="disabled">
                                {{ __('Deshabilitar autenticación') }}
                            </button>
                        </x-confirms-password>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>
</x-action-section>
