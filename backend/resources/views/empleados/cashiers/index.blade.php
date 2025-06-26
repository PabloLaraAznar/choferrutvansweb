@extends('adminlte::page')

@section('title', 'Cajeros')

@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-cash-register me-2"></i> Gestión de Cajeros
            </h1>
            <p class="subtitle">Administra los cajeros responsables de las ventas y pagos</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-money-bill-wave me-2"></i> Cajeros del Sistema
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateCashier">
                <i class="fas fa-plus"></i> Nuevo Cajero
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="table-responsive">
                <table class="table rutvans-table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th><i class="fas fa-user me-1"></i> Nombre</th>
                            <th><i class="fas fa-envelope me-1"></i> Correo</th>
                            <th><i class="fas fa-id-badge me-1"></i> Código</th>
                            <th><i class="fas fa-camera me-1"></i> Foto</th>
                            <th><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashiers as $cashier)
                            <tr>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-success">{{ $cashier->id }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    {{ $cashier->user->name ?? 'Sin usuario' }}
                                </td>
                                <td>
                                    <i class="fas fa-at text-muted me-2"></i>
                                    {{ $cashier->user->email ?? 'Sin correo' }}
                                </td>
                                <td>
                                    <span class="rutvans-badge rutvans-badge-warning">{{ $cashier->employee_code }}</span>
                                </td>
                                <td>
                                    @if ($cashier->photo)
                                        <img src="{{ asset('storage/' . $cashier->photo) }}"
                                            alt="Foto de {{ $cashier->user->name ?? '' }}"
                                            class="rounded-circle shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-light"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="rutvans-btn rutvans-btn-warning rutvans-btn-sm btn-edit-cashier"
                                            data-id="{{ $cashier->id }}" 
                                            data-name="{{ $cashier->user->name ?? '' }}"
                                            data-email="{{ $cashier->user->email ?? '' }}"
                                    data-photo="{{ $cashier->photo ? asset('storage/' . $cashier->photo) : '' }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('cashiers.destroy', $cashier) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('¿Seguro que quieres eliminar este cajeor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('empleados.cashiers.create')
    @include('empleados.cashiers.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalEditCashier = new bootstrap.Modal(document.getElementById('modalEditCashier'));
        const formEditCashier = document.getElementById('formEditCashier');

        document.querySelectorAll('.btn-edit-cashier').forEach(button => {
            button.addEventListener('click', function() {
                const cashier = this.dataset;

                document.getElementById('edit_cashier_id').value = cashier.id;
                document.getElementById('edit_name').value = cashier.name;
                document.getElementById('edit_email').value = cashier.email;
                document.getElementById('current_photo_preview').src = cashier.photo || '';

                formEditCashier.action = "{{ url('cashiers') }}/" + cashier.id;

                modalEditCashier.show();
            });
        });
    </script>
@endsection
