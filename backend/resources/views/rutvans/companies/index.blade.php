@extends('adminlte::page')

@section('title', 'Gestión de Empresas/Sindicatos')

@section('adminlte_css_pre')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content_header')
    <div class="d-flex justify-content-between align-items-center p-3 mb-3" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div>
            <h1 class="mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.8rem;">
                <i class="fas fa-industry me-2"></i> Gestión de Empresas/Sindicatos
            </h1>
            <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Administra las empresas y sindicatos que contratan Rutvans</p>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card shadow-sm" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
            <h3 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                <i class="fas fa-building me-2"></i> Empresas/Sindicatos Registrados
            </h3>
            <div class="ml-auto">
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#createCompanyModal" style="font-weight: 600;">
                    <i class="fas fa-plus mr-1"></i>
                    Nueva Empresa/Sindicato
                </button>
            </div>
        </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="companiesTable" style="border-radius: 8px; overflow: hidden;">
                            <thead style="background: linear-gradient(135deg, #6c757d, #495057); color: white;">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Razón Social</th>
                                    <th>RFC</th>
                                    <th>Localidad</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Sitios/Rutas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $company)
                                    <tr>
                                        <td>{{ $company->id }}</td>
                                        <td>
                                            <strong>{{ $company->name }}</strong>
                                        </td>
                                        <td>{{ $company->business_name ?: 'N/A' }}</td>
                                        <td>{{ $company->rfc ?: 'N/A' }}</td>
                                        <td>{{ $company->locality->locality ?? 'N/A' }}</td>
                                        <td>{{ $company->phone }}</td>
                                        <td>
                                            @if($company->status === 'active')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $company->sites->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-info btn-sm" 
                                                        onclick="viewCompany({{ $company->id }})" 
                                                        title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        onclick="editCompany({{ $company->id }})" 
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{ route('clients.index', ['company' => $company->id]) }}" 
                                                   class="btn btn-primary btn-sm" 
                                                   title="Gestionar sitios/rutas">
                                                    <i class="fas fa-route"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="deleteCompany({{ $company->id }}, '{{ $company->name }}')" 
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-industry fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No hay empresas/sindicatos registrados</h5>
                                                <p class="text-muted">Comienza agregando tu primera empresa/sindicato cliente.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($companies->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $companies->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
@include('rutvans.companies.create')
@include('rutvans.companies.edit')

@endsection

@section('css')
    {{-- Aquí puedes agregar hojas de estilo específicas --}}
@stop

@section('js')
<script>
$(document).ready(function() {
    // Inicializar DataTable con AdminLTE
    $('#companiesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[ 0, "desc" ]],
        "pageLength": 10,
        "responsive": true,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "buttons": []
    });
});

function viewCompany(companyId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.get(`{{ url('companies') }}/${companyId}`, function(data) {
        Swal.fire({
            title: '<i class="fas fa-industry"></i> Detalles de la Empresa/Sindicato',
            html: `
                <div class="row text-left">
                    <div class="col-md-6">
                        <h6><strong>Información General</strong></h6>
                        <p><strong>Nombre:</strong> ${data.company.name}</p>
                        <p><strong>Razón Social:</strong> ${data.company.business_name || 'N/A'}</p>
                        <p><strong>RFC:</strong> ${data.company.rfc || 'N/A'}</p>
                        <p><strong>Localidad:</strong> ${data.company.locality ? data.company.locality.locality : 'N/A'}</p>
                        <p><strong>Dirección:</strong> ${data.company.address}</p>
                        <p><strong>Teléfono:</strong> ${data.company.phone}</p>
                        <p><strong>Email:</strong> ${data.company.email || 'N/A'}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge badge-${data.company.status === 'active' ? 'success' : 'danger'}">
                                ${data.company.status === 'active' ? 'Activo' : 'Inactivo'}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><strong>Estadísticas</strong></h6>
                        <p><strong>Total de Sitios/Rutas:</strong> ${data.stats.sites}</p>
                        <p><strong>Sitios Activos:</strong> ${data.stats.active_sites}</p>
                        <p><strong>Total de Usuarios:</strong> ${data.stats.total_users}</p>
                        ${data.company.notes ? `<p><strong>Notas:</strong> ${data.company.notes}</p>` : ''}
                    </div>
                </div>
            `,
            width: 700,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#28a745'
        });
    }).fail(function(xhr) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo cargar la información de la empresa/sindicato',
            icon: 'error',
            confirmButtonColor: '#28a745'
        });
    });
}

function editCompany(companyId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.get(`{{ url('companies') }}/${companyId}`, function(data) {
        // Llenar el formulario de edición
        $('#editCompanyForm input[name="name"]').val(data.company.name);
        $('#editCompanyForm input[name="business_name"]').val(data.company.business_name);
        $('#editCompanyForm input[name="rfc"]').val(data.company.rfc);
        $('#editCompanyForm select[name="locality_id"]').val(data.company.locality_id);
        $('#editCompanyForm input[name="address"]').val(data.company.address);
        $('#editCompanyForm input[name="phone"]').val(data.company.phone);
        $('#editCompanyForm input[name="email"]').val(data.company.email);
        $('#editCompanyForm select[name="status"]').val(data.company.status);
        $('#editCompanyForm textarea[name="notes"]').val(data.company.notes);
        
        // Actualizar la acción del formulario
        $('#editCompanyForm').attr('action', `{{ url('companies') }}/${companyId}`);
        
        $('#editCompanyModal').modal('show');
    }).fail(function(xhr) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo cargar la información de la empresa/sindicato para editar',
            icon: 'error',
            confirmButtonColor: '#28a745'
        });
    });
}

function deleteCompany(companyId, companyName) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `¿Deseas eliminar la empresa/sindicato <strong>${companyName}</strong>?<br><br>
               <div class="alert alert-warning">
                   <i class="fas fa-exclamation-triangle"></i>
                   <strong>Advertencia:</strong> Esta acción también eliminará todos los sitios/rutas 
                   y usuarios asociados a esta empresa y es irreversible.
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario para DELETE
            let form = $('<form>', {
                'method': 'POST',
                'action': `{{ url('companies') }}/${companyId}`
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': $('meta[name="csrf-token"]').attr('content')
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_method',
                'value': 'DELETE'
            }));
            
            $('body').append(form);
            form.submit();
        }
    });
}
</script>
@stop
