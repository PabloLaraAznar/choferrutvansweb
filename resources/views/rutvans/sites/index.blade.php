@extends('adminlte::page')

@section('title', 'Gestión de Sitios/Terminales Rutvans')

@section('adminlte_css_pre')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content_header')
    <div class="d-flex justify-content-between align-items-center p-3 mb-3" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div>
            <h1 class="mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.8rem;">
                <i class="fas fa-building me-2"></i> Gestión de Sitios/Terminales
            </h1>
            <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Administra los sitios/terminales de las empresas/sindicatos</p>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
        <div class="card shadow-sm" style="border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #ff6600, #e55a00); color: white; border-radius: 12px 12px 0 0; padding: 1.5rem;">
            <h3 class="mb-0" style="font-family: 'Poppins', sans-serif; font-weight: 600;">
                <i class="fas fa-building me-2"></i> Sitios/Terminales Registrados
            </h3>
            <div class="ml-auto">
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#createSiteModal" style="font-weight: 600;">
                    <i class="fas fa-plus mr-1"></i>
                    Nuevo Sitio/Terminal
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

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="companyFilter" class="form-label">Filtrar por Empresa/Sindicato:</label>
                            <select class="form-control" id="companyFilter">
                                <option value="">Todas las empresas</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="statusFilter" class="form-label">Filtrar por Estado:</label>
                            <select class="form-control" id="statusFilter">
                                <option value="">Todos los estados</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="sitesTable" style="border-radius: 8px; overflow: hidden;">
                            <thead style="background: linear-gradient(135deg, #6c757d, #495057); color: white;">
                                <tr>
                                    <th>#</th>
                                    <th>Empresa/Sindicato</th>
                                    <th>Nombre del Sitio</th>
                                    <th>Ruta</th>
                                    <th>Localidad</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Admin</th>
                                    <th>Usuarios</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sites as $site)
                                    <tr>
                                        <td>{{ $site->id }}</td>
                                        <td>
                                            <strong>{{ $site->company->name ?? 'N/A' }}</strong>
                                            @if($site->company)
                                                <br><small class="text-muted">{{ $site->company->business_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $site->name }}</strong>
                                        </td>
                                        <td>
                                            @if($site->route_name)
                                                <strong>{{ $site->route_name }}</strong>
                                                <br><small class="text-muted">Ruta principal del sitio</small>
                                            @else
                                                <span class="text-muted">Sin ruta principal</span>
                                                <br><small class="text-muted">Se crearán rutas específicas después</small>
                                            @endif
                                        </td>
                                        <td>{{ $site->locality->locality ?? 'N/A' }}</td>
                                        <td>{{ $site->phone }}</td>
                                        <td>
                                            @if($site->status === 'active')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $admin = $site->users()->wherePivot('role', 'admin')->first();
                                            @endphp
                                            @if($admin)
                                                <div>
                                                    <strong>{{ $admin->name }}</strong><br>
                                                    <small class="text-muted">{{ $admin->email }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">Sin admin asignado</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $site->users->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-info btn-sm" 
                                                        onclick="viewSite({{ $site->id }})" 
                                                        title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        onclick="editSite({{ $site->id }})" 
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="deleteSite({{ $site->id }}, '{{ $site->name }}')" 
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                                @if(request('status') || request('company'))
                                                    <h5 class="text-muted">No se encontraron sitios/terminales</h5>
                                                    <p class="text-muted">
                                                        No hay sitios que coincidan con los filtros seleccionados.
                                                        <br>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearFilters()">
                                                            <i class="fas fa-times mr-1"></i>
                                                            Limpiar filtros
                                                        </button>
                                                    </p>
                                                @else
                                                    <h5 class="text-muted">No hay sitios/terminales registrados</h5>
                                                    <p class="text-muted">Comienza agregando tu primer sitio/terminal.</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($sites->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $sites->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
@include('rutvans.sites.create')
@include('rutvans.sites.edit')

@endsection

@section('css')
    {{-- Aquí puedes agregar hojas de estilo específicas --}}
@stop

@section('js')
<script>
$(document).ready(function() {
    let sitesDataTable = null;
    
    function initializeDataTable() {
        console.log('Iniciando inicialización de DataTable...');
        
        // Verificar si jQuery está disponible
        if (typeof $ === 'undefined') {
            console.error('jQuery no está disponible');
            return;
        }
        
        // Verificar si la tabla existe
        const table = $('#sitesTable');
        if (table.length === 0) {
            console.log('Tabla #sitesTable no encontrada');
            return;
        }
        
        // Verificar estructura de la tabla
        const thead = table.find('thead th');
        const tbody = table.find('tbody');
        const rows = tbody.find('tr');
        
        console.log(`Encabezados encontrados: ${thead.length}`);
        console.log(`Filas encontradas: ${rows.length}`);
        
        // Verificar si hay datos (excluyendo filas con colspan)
        const hasData = rows.length > 0 && !tbody.find('tr td[colspan]').length;
        
        if (!hasData) {
            console.log('La tabla no tiene datos válidos para DataTable');
            return;
        }
        
        // Verificar consistencia de filas
        let validTable = true;
        const expectedCells = thead.length;
        
        rows.each(function(index) {
            const cells = $(this).find('td').length;
            if (cells !== expectedCells) {
                console.error(`Fila ${index + 1}: ${cells} celdas, esperadas ${expectedCells}`);
                validTable = false;
            }
        });
        
        if (!validTable) {
            console.error('Estructura de tabla inconsistente');
            return;
        }
        
        try {
            // Destruir instancia existente
            if (sitesDataTable) {
                console.log('Destruyendo instancia existente de DataTable');
                sitesDataTable.destroy();
                sitesDataTable = null;
            }
            
            // Limpiar cualquier inicialización previa
            table.removeClass('dataTable');
            
            console.log('Inicializando DataTable...');
            
            // Inicializar DataTable con configuración básica
            sitesDataTable = table.DataTable({
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay sitios/terminales registrados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ sitios",
                    "infoEmpty": "Mostrando 0 a 0 de 0 sitios",
                    "infoFiltered": "(filtrado de _MAX_ sitios totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ sitios por página",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron sitios que coincidan",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "order": [[ 0, "desc" ]],
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "searching": true,
                "paging": true,
                "info": true,
                "autoWidth": false,
                "responsive": false, // Deshabilitamos responsive para evitar conflictos
                "processing": false,
                "serverSide": false,
                "columnDefs": [
                    {
                        "targets": [0],
                        "width": "5%",
                        "className": "text-center"
                    },
                    {
                        "targets": [6],
                        "width": "8%",
                        "className": "text-center"
                    },
                    {
                        "targets": [8],
                        "width": "8%",
                        "className": "text-center"
                    },
                    {
                        "targets": [9],
                        "width": "12%",
                        "orderable": false,
                        "searchable": false,
                        "className": "text-center"
                    }
                ],
                "initComplete": function(settings, json) {
                    console.log('DataTable inicializada correctamente');
                },
                "drawCallback": function(settings) {
                    console.log('DataTable dibujada');
                },
                "error": function(xhr, error, thrown) {
                    console.error('Error en DataTable:', error, thrown);
                }
            });
            
            console.log('✅ DataTable inicializada exitosamente');
            
        } catch (error) {
            console.error('❌ Error inicializando DataTable:', error);
            if (sitesDataTable) {
                try {
                    sitesDataTable.destroy();
                } catch (destroyError) {
                    console.error('Error destruyendo DataTable después de fallo:', destroyError);
                }
                sitesDataTable = null;
            }
        }
    }
    
    // Inicializar DataTable con un pequeño delay
    setTimeout(function() {
        initializeDataTable();
    }, 250);

    // Manejar filtros
    $('#companyFilter, #statusFilter').on('change', function() {
        console.log('Filtro cambiado, recargando página...');
        
        // Destruir DataTable antes de navegar
        if (sitesDataTable) {
            try {
                sitesDataTable.destroy();
            } catch (error) {
                console.error('Error destruyendo DataTable en filtro:', error);
            }
            sitesDataTable = null;
        }
        
        let company = $('#companyFilter').val();
        let status = $('#statusFilter').val();
        let url = '{{ route("clients.index") }}';
        let params = new URLSearchParams();
        
        if (company) params.append('company', company);
        if (status) params.append('status', status);
        
        window.location.href = url + (params.toString() ? '?' + params.toString() : '');
    });
});

// Función para limpiar filtros
function clearFilters() {
    window.location.href = '{{ route("clients.index") }}';
}

function viewSite(siteId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.get(`{{ url('clients') }}/${siteId}`, function(data) {
        // Usar SweetAlert2 que ya está incluido en AdminLTE
        Swal.fire({
            title: '<i class="fas fa-route"></i> Detalles del Sitio/Ruta',
            html: `
                <div class="row text-left">
                    <div class="col-md-6">
                        <h6><strong>Información del Sitio</strong></h6>
                        <p><strong>Empresa:</strong> ${data.site.company ? data.site.company.name : 'N/A'}</p>
                        <p><strong>Nombre:</strong> ${data.site.name}</p>
                        <p><strong>Ruta Principal:</strong> ${data.site.route_name || 'No especificada'}</p>
                        <p><strong>Localidad:</strong> ${data.site.locality ? data.site.locality.locality : 'N/A'}</p>
                        <p><strong>Dirección:</strong> ${data.site.address}</p>
                        <p><strong>Teléfono:</strong> ${data.site.phone}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge badge-${data.site.status === 'active' ? 'success' : 'danger'}">
                                ${data.site.status === 'active' ? 'Activo' : 'Inactivo'}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><strong>Estadísticas</strong></h6>
                        <p><strong>Conductores:</strong> ${data.stats.drivers}</p>
                        <p><strong>Cajeros:</strong> ${data.stats.cashiers}</p>
                        <p><strong>Coordinadores:</strong> ${data.stats.coordinates}</p>
                        <p><strong>Unidades:</strong> ${data.stats.units}</p>
                    </div>
                </div>
            `,
            width: 600,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#ff6600'
        });
    }).fail(function(xhr) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo cargar la información del sitio',
            icon: 'error',
            confirmButtonColor: '#ff6600'
        });
    });
}

function editSite(siteId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.get(`{{ url('clients') }}/${siteId}`, function(data) {
        // Llenar el formulario de edición
        $('#editSiteForm select[name="company_id"]').val(data.site.company_id);
        $('#editSiteForm input[name="name"]').val(data.site.name);
        $('#editSiteForm input[name="route_name"]').val(data.site.route_name);
        $('#editSiteForm select[name="locality_id"]').val(data.site.locality_id);
        $('#editSiteForm input[name="address"]').val(data.site.address);
        $('#editSiteForm input[name="phone"]').val(data.site.phone);
        $('#editSiteForm select[name="status"]').val(data.site.status);
        
        // Datos del admin - por defecto, mostrar opción de actualizar admin actual
        let admin = data.site.users.find(user => user.pivot.role === 'admin');
        if (admin) {
            // Seleccionar la opción de mantener/actualizar admin actual
            $('#edit_admin_new').prop('checked', true);
            $('#edit_admin_existing').prop('checked', false);
            
            $('#editSiteForm input[name="admin_name"]').val(admin.name);
            $('#editSiteForm input[name="admin_email"]').val(admin.email);
            
            // Mostrar la sección correcta
            $('#edit-existing-admin-section').hide();
            $('#edit-new-admin-section').show();
            
            // Configurar required
            $('#edit_admin_name').attr('required', 'required');
            $('#edit_admin_email').attr('required', 'required');
            $('#edit_existing_admin_id').removeAttr('required');
        } else {
            // Si no hay admin, por defecto mostrar opción de seleccionar existente
            $('#edit_admin_existing').prop('checked', true);
            $('#edit_admin_new').prop('checked', false);
            
            // Mostrar la sección correcta
            $('#edit-existing-admin-section').show();
            $('#edit-new-admin-section').hide();
            
            // Configurar required
            $('#edit_existing_admin_id').attr('required', 'required');
            $('#edit_admin_name').removeAttr('required');
            $('#edit_admin_email').removeAttr('required');
        }
        
        // Actualizar la acción del formulario
        $('#editSiteForm').attr('action', `{{ url('clients') }}/${siteId}`);
        
        $('#editSiteModal').modal('show');
    }).fail(function(xhr) {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo cargar la información del sitio para editar',
            icon: 'error',
            confirmButtonColor: '#ff6600'
        });
    });
}

function deleteSite(siteId, siteName) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `¿Deseas eliminar el sitio/ruta <strong>${siteName}</strong>?<br><br>
               <div class="alert alert-warning">
                   <i class="fas fa-exclamation-triangle"></i>
                   <strong>Advertencia:</strong> Esta acción también eliminará todos los usuarios asociados 
                   exclusivamente a este sitio y es irreversible.
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
                'action': `{{ url('clients') }}/${siteId}`
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
