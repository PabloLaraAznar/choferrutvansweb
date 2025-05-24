@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
@stop

@section('content')
<div id="profileContainer">
    <div class="container py-4">
        <div class="btn-group mb-4" role="group" aria-label="Secciones de perfil">
            <button type="button" id="btn-profile" class="btn btn-info" onclick="showSection('profile')" aria-label="Información del perfil">
                <i class="fas fa-user me-2"></i> Información del perfil
            </button>
            <button type="button" id="btn-password" class="btn btn-warning" onclick="showSection('password')" aria-label="Actualizar contraseña">
                <i class="fas fa-lock me-2"></i> Actualizar contraseña
            </button>
            <button type="button" id="btn-2fa" class="btn btn-success" onclick="showSection('2fa')" aria-label="Autenticación 2FA">
                <i class="fas fa-shield-alt me-2"></i> Autenticación 2FA
            </button>
            <button type="button" id="btn-sessions" class="btn btn-secondary" onclick="showSection('sessions')" aria-label="Cerrar sesiones">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesiones
            </button>
            <button type="button" id="btn-delete" class="btn btn-danger" onclick="showSection('delete')" aria-label="Eliminar cuenta">
                <i class="fas fa-trash-alt me-2"></i> Eliminar cuenta
            </button>
        </div>

        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            <div id="profile" class="profile-section">
                @livewire('profile.update-profile-information-form')
                <hr>
            </div>
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div id="password" class="profile-section d-none">
                @livewire('profile.update-password-form')
                <hr>
            </div>
        @endif

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div id="2fa" class="profile-section d-none">
                @livewire('profile.two-factor-authentication-form')
                <hr>
            </div>
        @endif

        <div id="sessions" class="profile-section d-none">
            @livewire('profile.logout-other-browser-sessions-form')
            <hr>
        </div>

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <div id="delete" class="profile-section d-none">
                @livewire('profile.delete-user-form')
            </div>
        @endif
    </div>
</div>

<script>
    function showSection(section) {
        // Ocultar todas las secciones
        document.querySelectorAll('.profile-section').forEach(el => el.classList.add('d-none'));
        document.getElementById(section).classList.remove('d-none');

        // Remover la clase 'active' de todos los botones
        document.querySelectorAll('.btn').forEach(button => {
            button.classList.remove('active');
        });

        // Agregar la clase 'active' al botón correspondiente
        const activeButton = document.getElementById('btn-' + section);
        activeButton.classList.add('active');
    }
</script>

<style>
    /* Estilo para el botón activo con animación suave */
    .btn.active {
        transform: scale(1.1);
        font-weight: bold;
        transition: transform 0.3s ease, font-weight 0.3s ease; /* Suaviza la transición */
    }
</style>
@endsection
