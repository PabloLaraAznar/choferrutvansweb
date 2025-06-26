# 🎨 PLANTILLA RUTVANS PARA VISTAS CRUD

Esta plantilla te ayuda a aplicar rápidamente el diseño RUTVANS a cualquier vista CRUD.

## 📋 Checklist de Aplicación

### 1. Content Header (Cabecera)
```blade
@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="fas fa-[ICONO] me-2"></i> [TÍTULO]
            </h1>
            <p class="subtitle">[DESCRIPCIÓN]</p>
        </div>
    </div>
@endsection
```

### 2. Content Principal
```blade
@section('content')
    <div class="rutvans-card rutvans-hover-lift rutvans-fade-in">
        <div class="rutvans-card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">
                <i class="fas fa-[ICONO] me-2"></i> [TÍTULO DE CARD]
            </h3>
            <button type="button" class="rutvans-btn rutvans-btn-primary" data-bs-toggle="modal" data-bs-target="#[MODAL]">
                <i class="fas fa-plus"></i> [TEXTO BOTÓN]
            </button>
        </div>
        
        <div class="rutvans-card-body">
            <div class="table-responsive">
                <table class="table rutvans-table table-striped table-hover align-middle">
                    <!-- Tu contenido de tabla aquí -->
                </table>
            </div>
        </div>
    </div>
@endsection
```

### 3. CSS Section
```blade
@section('css')
    <link href="{{ asset('css/rutvans-admin.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: var(--rutvans-background);
        }
        
        .content-wrapper {
            background-color: var(--rutvans-background);
        }
    </style>
@endsection
```

### 4. Tabla Estándar
```blade
<table class="table rutvans-table table-striped table-hover align-middle">
    <thead>
        <tr>
            <th><i class="fas fa-hashtag me-1"></i> ID</th>
            <th><i class="fas fa-[ICONO] me-1"></i> [COLUMNA]</th>
            <th><i class="fas fa-cogs me-1"></i> Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>
                    <span class="rutvans-badge rutvans-badge-primary">{{ $item->id }}</span>
                </td>
                <td>
                    <i class="fas fa-[ICONO] text-muted me-2"></i>
                    {{ $item->campo }}
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="rutvans-btn rutvans-btn-warning rutvans-btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

### 5. Botones Estándar
```blade
<!-- Botón Principal -->
<button class="rutvans-btn rutvans-btn-primary">
    <i class="fas fa-plus"></i> Crear
</button>

<!-- Botón Editar -->
<button class="rutvans-btn rutvans-btn-warning rutvans-btn-sm">
    <i class="fas fa-edit"></i> Editar
</button>

<!-- Botón Eliminar -->
<button class="rutvans-btn rutvans-btn-danger rutvans-btn-sm">
    <i class="fas fa-trash"></i> Eliminar
</button>

<!-- Botón Exportar -->
<button class="rutvans-btn rutvans-btn-success">
    <i class="fas fa-file-excel"></i> Excel
</button>
```

### 6. Modal Headers
```blade
<!-- Modal Crear -->
<div class="rutvans-modal-header">
    <h5 class="modal-title">
        <i class="fas fa-plus-circle me-2"></i> Agregar [ITEM]
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<!-- Modal Editar -->
<div class="rutvans-modal-header" style="background: linear-gradient(135deg, #17a2b8, #138496);">
    <h5 class="modal-title">
        <i class="fas fa-edit me-2"></i> Editar [ITEM]
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
```

### 7. Form Groups
```blade
<div class="rutvans-form-group">
    <label for="campo" class="rutvans-form-label">
        <i class="fas fa-[ICONO] me-1"></i> [ETIQUETA]
    </label>
    <input type="text" class="form-control rutvans-form-control" id="campo" name="campo" required
        placeholder="[PLACEHOLDER]">
    <div class="invalid-feedback">Este campo es obligatorio.</div>
</div>
```

## 🎨 Esquema de Colores RUTVANS

- **Primario**: `#ff6600` (Naranja)
- **Secundario**: `#000000` (Negro)
- **Fondo**: `#f0f2f5` (Gris claro)
- **Éxito**: `#28a745` (Verde)
- **Info**: `#17a2b8` (Azul)
- **Advertencia**: `#ffc107` (Amarillo)
- **Peligro**: `#dc3545` (Rojo)

## 📝 Iconos por Categoría

- **Usuarios**: `fas fa-users`, `fas fa-user-circle`
- **Empleados**: `fas fa-id-card`, `fas fa-user-tie`
- **Transporte**: `fas fa-bus`, `fas fa-shuttle-van`
- **Rutas**: `fas fa-route`, `fas fa-map-marked-alt`
- **Horarios**: `fas fa-clock`, `fas fa-business-time`
- **Pagos**: `fas fa-money-check-alt`, `fas fa-cash-register`
- **Reportes**: `fas fa-chart-bar`, `fas fa-file-alt`

## 🚀 Vistas Pendientes de Actualizar

- [ ] `localidades/index.blade.php`
- [ ] `rutaunidad/index.blade.php`
- [ ] `tarifas/index.blade.php`
- [ ] `roles-permissions/roles/index.blade.php`
- [ ] `roles-permissions/permissions/index.blade.php`
- [ ] `roles-permissions/roles-permissions/index.blade.php`

## 📖 Instrucciones de Uso

1. Copia el template correspondiente
2. Reemplaza `[ICONO]`, `[TÍTULO]`, etc. con tus valores
3. Agrega la sección CSS con `rutvans-admin.css`
4. Aplica las clases `rutvans-*` a tu contenido
5. Verifica que el diseño sea consistente con el login

¡Listo! Tu vista tendrá el diseño RUTVANS unificado.
