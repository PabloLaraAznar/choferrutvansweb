<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Información de perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Actualice la información del perfil y la dirección de correo electrónico de su cuenta.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Contenedor para foto de perfil y nombre -->
        <div class="card-body">
            <div class="row">
                <!-- Profile Photo -->
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div x-data="{ photoName: null, photoPreview: null }" class="col-md-4 mb-3 text-center">
                        <input type="file" id="photo" class="d-none"
                                wire:model.live="photo"
                                x-ref="photo"
                                x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                " />

                        <x-label for="photo" value="{{ __('Foto de perfil') }}" />

                        <div class="mt-2">
                            <img :src="photoPreview || '{{ $this->user->profile_photo_url }}'" alt="{{ $this->user->name }}"  class="rounded-circle" width="200">
                        </div>

                        <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                            <i class="fas fa-camera me-2"></i> {{ __('Actualizar mi foto') }}
                        </x-secondary-button>

                        @if ($this->user->profile_photo_path)
                        <x-secondary-button type="button" class="mt-2 me-2" 
                        x-on:click="if(confirm('{{ __('¿Estás seguro de que deseas eliminar tu foto de perfil?') }}')) { $wire.deleteProfilePhoto() }">
                        <i class="fas fa-trash-alt me-2"></i> {{ __('Eliminar esta foto') }}
                    </x-secondary-button>
                    
                        @endif

                        <x-input-error for="photo" class="mt-2" />
                        <!-- Botón de actualización dentro del mismo bloque -->
                        <div class="mt-4 text-end">
                            <x-action-message class="me-3 text-success fw-bold" on="saved">
                                <i class="fas fa-check-circle me-2"></i>{{ __('Actualización exitosa.') }}
                            </x-action-message>
            
                            <x-button wire:loading.attr="disabled" wire:target="photo" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> {{ __('Actualizar') }}
                            </x-button>
                        </div>
                    </div>
                @endif

                <!-- Nombre y otros campos dentro de una columna -->
                <div class="col-md-8 mb-3">
                    <!-- Name -->
                    <div>
                        <x-label for="name" value="{{ __('Nombre') }}" />
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <x-input id="name" type="text" class="form-control" wire:model="state.name" required autocomplete="name" />
                        </div>
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-3">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <x-input id="email" type="email" class="form-control" wire:model="state.email" required autocomplete="username" />
                        </div>
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    <!-- Phone Number -->
                    <div class="mt-3">
                        <x-label for="phone_number" value="{{ __('Número de teléfono') }}" />
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <x-input id="phone_number" type="text" class="form-control" wire:model="state.phone_number" required autocomplete="phone_number" />
                        </div>
                        <x-input-error for="phone_number" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div class="mt-3">
                        <x-label for="address" value="{{ __('Dirección') }}" />
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <x-input id="address" type="text" class="form-control" wire:model="state.address" required autocomplete="address" />
                        </div>
                        <x-input-error for="address" class="mt-2" />
                    </div>
                </div>
            </div>


        </div>
    </x-slot>
</x-form-section>