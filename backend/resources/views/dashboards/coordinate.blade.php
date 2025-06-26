@extends('adminlte::page')

@section('title', 'Dashboard Coordinador')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Dashboard - Coordinador</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie"></i>
                        Bienvenido, Coordinador {{ auth()->user()->name }}
                    </h3>
                </div>
                <div class="card-body">
                    <p class="lead">Panel de control para coordinadores de RutVans</p>
                    <p>Desde aquí puedes gestionar rutas, unidades, conductores y supervisar las operaciones diarias.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    @php
                        try {
                            $conductoresCount = \App\Models\User::role('driver')->count();
                        } catch (\Exception $e) {
                            $conductoresCount = 0;
                        }
                    @endphp
                    <h3>{{ $conductoresCount }}</h3>
                    <p>Conductores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('drivers.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    @php
                        try {
                            $cajerosCount = \App\Models\User::role('cashier')->count();
                        } catch (\Exception $e) {
                            $cajerosCount = 0;
                        }
                    @endphp
                    <h3>{{ $cajerosCount }}</h3>
                    <p>Cajeros</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <a href="{{ route('cashiers.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    @php
                        try {
                            $coordinadoresCount = \App\Models\User::role('coordinate')->count();
                        } catch (\Exception $e) {
                            $coordinadoresCount = 0;
                        }
                    @endphp
                    <h3>{{ $coordinadoresCount }}</h3>
                    <p>Coordinadores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <a href="{{ route('coordinates.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    @php
                        try {
                            $totalUsers = \App\Models\User::count();
                        } catch (\Exception $e) {
                            $totalUsers = 0;
                        }
                    @endphp
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <a href="{{ route('usuarios.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('route_unit_schedule.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-calendar-alt"></i> Calendario
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('sales.history') }}" class="btn btn-success btn-block">
                                <i class="fas fa-chart-line"></i> Ventas
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('drivers.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-user-plus"></i> Conductores
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('units.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-bus"></i> Unidades
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Información del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Rol:</strong>
                            <span class="badge badge-primary badge-pill">Coordinador</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Usuario:</strong>
                            <span>{{ auth()->user()->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Email:</strong>
                            <span>{{ auth()->user()->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Último acceso:</strong>
                            <span>{{ now()->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.small-box {
    border-radius: 10px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
}

.btn-block {
    margin-bottom: 10px;
}

.list-group-item {
    border: none;
    padding: 10px 0;
}
</style>
@stop

@section('js')
<script>
    console.log('Dashboard de Coordinador cargado correctamente');
    
    // Mensaje de bienvenida
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@stop
