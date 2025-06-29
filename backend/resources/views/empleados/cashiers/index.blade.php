@extends('adminlte::page')

@section('title', 'Cajeros')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mb-4" style="background: linear-gradient(135deg, #ff6600 0%, #ff8533 100%); padding: 1.5rem; border-radius: 15px; box-shadow: 0 8px 25px rgba(255, 102, 0, 0.15); animation: fadeInDown 0.6s ease-out;">
        <div>
            <h1 class="text-white mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 2rem;">
                <i class="fas fa-cash-register me-3"></i> Gestión de Cajeros
            </h1>
            <p class="text-white mb-0" style="font-family: 'Poppins', sans-serif; opacity: 0.9; font-size: 1.1rem;">
                Administra los cajeros responsables de las ventas y pagos
            </p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 15px 15px 0 0; padding: 1.5rem;">
            <h3 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                <i class="fas fa-money-bill-wave me-2"></i> Cajeros del Sistema
            </h3>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalCreateCashier" style="font-weight: 600; border-radius: 8px; padding: 0.5rem 1.5rem;">
                <i class="fas fa-plus text-primary"></i> Nuevo Cajero
            </button>
        </div>
        
        <div class="card-body" style="padding: 2rem;">
            <div class="table-responsive">
                <table id="cashiersTable" class="table table-hover table-striped" style="border-radius: 8px; overflow: hidden;">
                    <thead style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <tr>
                            <th class="fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;"><i class="fas fa-hashtag me-1"></i> ID</th>
                            <th class="fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;"><i class="fas fa-user me-1"></i> Nombre</th>
                            <th class="fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;"><i class="fas fa-envelope me-1"></i> Correo</th>
                            <th class="fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;"><i class="fas fa-id-badge me-1"></i> Código</th>
                            <th class="fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;"><i class="fas fa-camera me-1"></i> Foto</th>
                            <th class="fw-bold" style="color: #495057; font-family: 'Poppins', sans-serif;"><i class="fas fa-cogs me-1"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashiers as $cashier)
                            <tr style="transition: all 0.3s ease;">
                                <td>
                                    <span class="badge bg-success" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">{{ $cashier->id }}</span>
                                </td>
                                <td style="font-weight: 500;">
                                    <i class="fas fa-user-circle text-muted me-2"></i>
                                    {{ $cashier->user->name ?? 'Sin usuario' }}
                                </td>
                                <td style="font-weight: 500;">
                                    <i class="fas fa-at text-muted me-2"></i>
                                    {{ $cashier->user->email ?? 'Sin correo' }}
                                </td>
                                <td>
                                    <span class="badge bg-warning" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">{{ $cashier->employee_code }}</span>
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
                                        <button type="button" class="btn btn-warning btn-sm btn-edit-cashier"
                                            data-id="{{ $cashier->id }}" 
                                            data-name="{{ $cashier->user->name ?? '' }}"
                                            data-email="{{ $cashier->user->email ?? '' }}"
                                            data-employee-code="{{ $cashier->employee_code }}"
                                            data-photo="{{ $cashier->photo ? asset('storage/' . $cashier->photo) : '' }}"
                                            style="border-radius: 6px; font-weight: 500;">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        
                                        <form action="{{ route('cashiers.destroy', $cashier) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Seguro que quieres eliminar este cajero?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit" style="border-radius: 6px; font-weight: 500;">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('empleados.cashiers.create')
    @include('empleados.cashiers.edit')
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .content-wrapper {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#cashiersTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false
            });
        });

        const modalEditCashier = new bootstrap.Modal(document.getElementById('modalEditCashier'));
        const formEditCashier = document.getElementById('formEditCashier');

        document.querySelectorAll('.btn-edit-cashier').forEach(button => {
            button.addEventListener('click', function() {
                const cashier = this.dataset;

                // Llenar los campos del formulario
                document.getElementById('edit_cashier_id').value = cashier.id;
                document.getElementById('edit_name').value = cashier.name;
                document.getElementById('edit_email').value = cashier.email;
                document.getElementById('edit_employee_code').value = cashier.employeeCode;
                
                // Manejar la foto
                const photoPreview = document.getElementById('current_photo_preview');
                const noPhotoIcon = document.getElementById('no_photo_icon');
                
                if (cashier.photo && cashier.photo !== '') {
                    photoPreview.src = cashier.photo;
                    photoPreview.style.display = 'block';
                    noPhotoIcon.style.display = 'none';
                } else {
                    photoPreview.style.display = 'none';
                    noPhotoIcon.style.display = 'block';
                }

                // Configurar la acción del formulario
                formEditCashier.action = "{{ url('cashiers') }}/" + cashier.id;

                // Mostrar el modal
                modalEditCashier.show();
            });
        });
    </script>
@endsection
