# Script PowerShell para aplicar diseño RUTVANS a todas las vistas CRUD
# Uso: .\apply-rutvans-design.ps1

$baseDir = "resources\views"
$vistasActualizar = @(
    "horarios\index.blade.php",
    "localidades\index.blade.php", 
    "rutaunidad\index.blade.php",
    "tarifas\index.blade.php",
    "roles-permissions\roles\index.blade.php",
    "roles-permissions\permissions\index.blade.php",
    "roles-permissions\roles-permissions\index.blade.php"
)

# Función para aplicar header RUTVANS
function Apply-RutvansHeader {
    param($filePath, $title, $icon, $subtitle)
    
    $headerPattern = '@section\(''content_header''\).*?@endsection'
    $newHeader = @"
@section('content_header')
    <div class="rutvans-content-header rutvans-fade-in">
        <div class="container-fluid">
            <h1>
                <i class="$icon me-2"></i> $title
            </h1>
            <p class="subtitle">$subtitle</p>
        </div>
    </div>
@endsection
"@
    
    $content = Get-Content $filePath -Raw
    $content = $content -replace $headerPattern, $newHeader
    Set-Content $filePath $content
}

# Función para aplicar CSS RUTVANS
function Apply-RutvansCss {
    param($filePath)
    
    $cssPattern = '@section\(''css''\).*?@endsection'
    $newCss = @"
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
"@
    
    $content = Get-Content $filePath -Raw
    if ($content -match $cssPattern) {
        $content = $content -replace $cssPattern, $newCss
    } else {
        $content += "`n`n$newCss"
    }
    Set-Content $filePath $content
}

# Aplicar cambios a cada vista
foreach ($vista in $vistasActualizar) {
    $fullPath = Join-Path $baseDir $vista
    if (Test-Path $fullPath) {
        Write-Host "Actualizando: $vista" -ForegroundColor Green
        
        # Determinar título, icono y subtítulo según la vista
        switch -Wildcard ($vista) {
            "*horarios*" {
                Apply-RutvansHeader $fullPath "Gestión de Horarios" "fas fa-clock" "Administra los horarios del sistema de transporte"
            }
            "*localidades*" {
                Apply-RutvansHeader $fullPath "Gestión de Localidades" "fas fa-map-marker-alt" "Administra las localidades y ubicaciones del sistema"
            }
            "*rutaunidad*" {
                Apply-RutvansHeader $fullPath "Rutas y Unidades" "fas fa-bus-alt" "Gestiona la asignación de unidades a rutas"
            }
            "*tarifas*" {
                Apply-RutvansHeader $fullPath "Gestión de Tarifas" "fas fa-dollar-sign" "Administra las tarifas del sistema de transporte"
            }
            "*roles\*" {
                Apply-RutvansHeader $fullPath "Gestión de Roles" "fas fa-user-tag" "Administra los roles del sistema"
            }
            "*permissions*" {
                Apply-RutvansHeader $fullPath "Gestión de Permisos" "fas fa-key" "Administra los permisos del sistema"
            }
            "*roles-permissions*" {
                Apply-RutvansHeader $fullPath "Asignación de Permisos" "fas fa-user-shield" "Asigna permisos a roles del sistema"
            }
        }
        
        Apply-RutvansCss $fullPath
        
    } else {
        Write-Warning "No se encontró: $fullPath"
    }
}

Write-Host "`nDiseño RUTVANS aplicado exitosamente!" -ForegroundColor Cyan
Write-Host "Recuerda hacer commit de los cambios:" -ForegroundColor Yellow
Write-Host "git add ." -ForegroundColor Gray
Write-Host "git commit -m 'feat: Aplicar diseño RUTVANS a todas las vistas CRUD'" -ForegroundColor Gray
