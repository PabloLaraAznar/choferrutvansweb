@extends('adminlte::page')

@section('title', 'Cajeros')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">
            <i class="fas fa-user-shield me-2 text-primary"></i> Gestión de Cajeros
        </h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateCashier">
            <i class="fas fa-plus me-1"></i> Nuevo Cajero
        </button>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">Lista de Cajeros</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Código de empleado</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cashiers as $cashier)
                        <tr>
                            <td>{{ $cashier->id }}</td>
                            <td>{{ $cashier->user->name ?? 'Sin usuario' }}</td>
                            <td>{{ $cashier->user->email ?? 'Sin correo' }}</td>
                            <td>{{ $cashier->employee_code }}</td>
                            <td>
                                @if ($cashier->photo)
                                    <img src="{{ asset('storage/' . $cashier->photo) }}"
                                        alt="Foto de {{ $cashier->user->name ?? '' }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span class="text-muted">Sin foto</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-edit-cashier"
                                    data-id="{{ $cashier->id }}" data-name="{{ $cashier->user->name ?? '' }}"
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
