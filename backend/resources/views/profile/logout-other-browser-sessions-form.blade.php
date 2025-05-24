<x-action-section>
    <x-slot name="title">
        {{ __('Sesiones del navegador') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Gestiona y cierra las sesiones activas en otros navegadores y dispositivos.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-muted">
            {{ __('Si es necesario, puedes cerrar todas tus sesiones en otros navegadores en todos tus dispositivos. Algunas de tus sesiones recientes se muestran a continuación; sin embargo, esta lista puede no ser exhaustiva. Si crees que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="mt-5 space-y-6">
                <!-- Otras sesiones del navegador -->
                @foreach ($this->sessions as $session)
                    <div class="d-flex align-items-center">
                        <div>
                            @if ($session->agent->isDesktop())
                                <i class="fas fa-desktop text-muted" style="font-size: 24px;"></i>
                            @else
                                <i class="fas fa-mobile-alt text-muted" style="font-size: 24px;"></i>
                            @endif
                        </div>

                        <div class="ms-3">
                            <div class="text-sm text-muted">
                                {{ $session->agent->platform() ? $session->agent->platform() : __('Desconocido') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Desconocido') }}
                            </div>

                            <div>
                                <div class="text-xs text-muted">
                                    {{ $session->ip_address }},
                                    @if ($session->is_current_device)
                                        <span class="text-success font-weight-bold">{{ __('Este dispositivo') }}</span>
                                    @else
                                        {{ __('Última actividad') }} {{ $session->last_active }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex align-items-center mt-5">
            <x-button id="openModalBtn1" class="btn btn-primary">
                {{ __('Cerrar sesión en otras sesiones del navegador') }}
            </x-button>

            <x-action-message class="ms-3" on="loggedOut">
                {{ __('Hecho.') }}
            </x-action-message>
        </div>

        <!-- Modal de confirmación para cerrar sesión en otros dispositivos -->
        <div id="confirmingLogout" class="modal" style="display: none;">
            <div class="modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
                <div class="modal-content" style="background-color: #fff; padding: 40px 50px; border-radius: 12px; width: 500px; max-width: 90%; box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15); overflow: hidden;">
                    <span class="close" style="position: absolute; top: 20px; right: 20px; font-size: 28px; font-weight: 700; cursor: pointer; color: #888; transition: color 0.3s ease;">
                        &times;
                    </span>
                    <h2 style="font-size: 1.75rem; font-weight: 600; margin-bottom: 25px; color: #333; text-align: center; letter-spacing: 0.5px;">{{ __('Cerrar sesión en otras sesiones del navegador') }}</h2>
                    <p style="font-size: 1rem; color: #000000; line-height: 1.6; margin-bottom: 35px; text-align: center;">
                        {{ __('Por favor, ingresa tu contraseña para confirmar que deseas cerrar sesión en todas tus otras sesiones del navegador en todos tus dispositivos.') }}
                    </p>
        
                    <div class="input-container" style="margin-bottom: 35px;">
                        <x-input type="password" class="form-control w-full p-4 border-2 border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                 autocomplete="current-password"
                                 placeholder="{{ __('Contraseña') }}"
                                 x-ref="password"
                                 wire:model="password"
                                 wire:keydown.enter="logoutOtherBrowserSessions"
                                 style="font-size: 1rem; padding: 15px; transition: all 0.3s ease;" />
                        <x-input-error for="password" class="mt-2 text-red-500" />
                    </div>
        
                    <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <x-secondary-button id="cancelModalBtn1" wire:loading.attr="disabled" class="btn btn-secondary" 
                                            style="padding: 12px 30px; border-radius: 6px; background-color: #f8f8f8; color: #333; font-weight: 500; border: 1px solid #ddd; transition: background-color 0.3s ease;">
                            {{ __('Cancelar') }}
                        </x-secondary-button>
        
                        <x-button class="ms-3 btn btn-danger" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled" 
                                  style="padding: 12px 30px; border-radius: 6px; background-color: #e74c3c; color: #fff; font-weight: 500; border: 1px solid #e74c3c; transition: background-color 0.3s ease;">
                            {{ __('Cerrar sesión en otros navegadores') }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
        
    </x-slot>
</x-action-section>

<!-- Script para Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById("confirmingLogout");
        var openModalBtn1 = document.getElementById("openModalBtn1");
        var closeModalBtn1 = document.getElementsByClassName("close")[0];
        var cancelModalBtn1 = document.getElementById("cancelModalBtn1");

        // Abre el modal cuando se haga clic en el botón
        openModalBtn1.addEventListener('click', function() {
            modal.style.display = "block";
        });

        // Cierra el modal cuando se haga clic en la "×"
        closeModalBtn1.addEventListener('click', function() {
            modal.style.display = "none";
        });

        // Cierra el modal cuando se haga clic en el botón de cancelar
        cancelModalBtn1.addEventListener('click', function() {
            modal.style.display = "none";
        });

        // Cierra el modal si se hace clic fuera de la ventana modal
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
</script>
