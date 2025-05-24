<x-action-section>
    <x-slot name="title">
        {{ __('Eliminar cuenta') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Elimina tu cuenta permanentemente.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-muted">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
        </div>

        <div class="mt-4">
            <x-danger-button id="openModalBtn" class="btn btn-danger">
                {{ __('Eliminar cuenta') }}
            </x-danger-button>
        </div>

        <!-- Modal de confirmación de eliminación de cuenta -->
        <div id="confirmDeletionModal" class="modal" style="display: none;">
            <div class="modal-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; z-index: 1000;">
                <div class="modal-content" style="background-color: #fff; padding: 40px 50px; border-radius: 12px; width: 500px; max-width: 90%; box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2); overflow: hidden;">
                    
                    <!-- Icono de cierre con Font Awesome -->
                    <span id="closeModalBtn" class="close" style="position: absolute; top: 20px; right: 20px; font-size: 28px; font-weight: 700; cursor: pointer; color: #555; transition: color 0.3s ease;">
                        <i class="fas fa-times"></i>
                    </span>
                    
                    <h2 style="font-size: 1.75rem; font-weight: 600; margin-bottom: 25px; color: #333; text-align: center; letter-spacing: 0.5px;">
                        {{ __('Confirmar eliminación de cuenta') }}
                    </h2>
                    
                    <p style="font-size: 1rem; color: #333; line-height: 1.6; margin-bottom: 35px; text-align: center;">
                        {{ __('¿Estás seguro de que deseas eliminar tu cuenta? Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
                    </p>
        
                    <div class="input-container" style="margin-bottom: 35px;">
                        
                        <x-input type="password" class="form-control w-full p-4 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                 autocomplete="current-password"
                                 placeholder="{{ __('Contraseña') }}"
                                 x-ref="password"
                                 wire:model="password"
                                 wire:keydown.enter="deleteUser"
                                 style="font-size: 1rem; padding: 15px; transition: all 0.3s ease;" />
                        <x-input-error for="password" class="mt-2 text-red-500" />
                    </div>
        
                    <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center;">
                        <!-- Botón Cancelar con estilo suave -->
                        <x-secondary-button id="cancelModalBtn" wire:loading.attr="disabled" class="btn btn-secondary" 
                                            style="padding: 12px 30px; border-radius: 6px; background-color: #f1f1f1; color: #333; font-weight: 500; border: 1px solid #ccc; transition: background-color 0.3s ease;">
                            {{ __('Cancelar') }}
                        </x-secondary-button>
                
                        <!-- Botón Eliminar cuenta con color rojo -->
                        <x-danger-button class="ms-3 btn btn-danger" wire:click="deleteUser" wire:loading.attr="disabled" 
                                         style="padding: 12px 30px; border-radius: 6px; background-color: #e74c3c; color: #fff; font-weight: 500; border: 1px solid #e74c3c; transition: background-color 0.3s ease;">
                            {{ __('Eliminar cuenta') }}
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
        

        
    </x-slot>
</x-action-section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById("confirmDeletionModal");
        var openModalBtn = document.getElementById("openModalBtn");
        var closeModalBtn = document.getElementById("closeModalBtn");
        var cancelModalBtn = document.getElementById("cancelModalBtn");

        // Abre el modal cuando se haga clic en el botón de eliminar cuenta
        openModalBtn.addEventListener('click', function() {
            modal.style.display = "block";
        });

        // Cierra el modal cuando se haga clic en la "×"
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = "none";
        });

        // Cierra el modal cuando se haga clic en el botón de cancelar
        cancelModalBtn.addEventListener('click', function() {
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
