<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Actualizar contraseña') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerla segura.') }}
    </x-slot>

    <x-slot name="form">
        <div class="card p-4">
            <div class="mb-3">
                <x-label for="current_password" value="{{ __('Contraseña actual') }}" class="form-label" />
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <x-input id="current_password" type="password" class="form-control" wire:model="state.current_password" autocomplete="current-password" />
                </div>
                <x-input-error for="current_password" class="text-danger mt-1" />
            </div>

            <div class="mb-3">
                <x-label for="password" value="{{ __('Nueva contraseña') }}" class="form-label" />
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <x-input id="password" type="password" class="form-control" wire:model="state.password" autocomplete="new-password" />
                </div>
                <x-input-error for="password" class="text-danger mt-1" />
            </div>

            <div class="mb-3">
                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" class="form-label" />
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                    <x-input id="password_confirmation" type="password" class="form-control" wire:model="state.password_confirmation" autocomplete="new-password" />
                </div>
                <x-input-error for="password_confirmation" class="text-danger mt-1" />
            </div>

            <div class="text-end">
                <x-action-message class="me-3" on="saved">
                    {{ __('Actualizado.') }}
                </x-action-message>

                <x-button class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> {{ __('Actualizar') }}
                </x-button>
            </div>
        </div>
    </x-slot>
</x-form-section>
