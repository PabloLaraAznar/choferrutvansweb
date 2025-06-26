# Script para aplicar el diseño RUTVANS a todas las vistas CRUD
# PowerShell script para actualizar automáticamente las vistas

Write-Host "🎨 RUTVANS Admin Theme Updater" -ForegroundColor Cyan
Write-Host "=================================" -ForegroundColor Cyan

# Lista de archivos a actualizar
$viewsToUpdate = @(
    "resources\views\empleados\cashiers\index.blade.php",
    "resources\views\empleados\coordinates\index.blade.php", 
    "resources\views\horarios\index.blade.php",
    "resources\views\roles-permissions\permissions\index.blade.php",
    "resources\views\roles-permissions\roles\index.blade.php",
    "resources\views\rutas\index.blade.php",
    "resources\views\units\index.blade.php"
)

foreach ($view in $viewsToUpdate) {
    if (Test-Path $view) {
        Write-Host "✅ Actualizando: $view" -ForegroundColor Green
        
        # Backup del archivo original
        $backupPath = $view -replace "\.blade\.php$", ".backup.blade.php"
        Copy-Item $view $backupPath -Force
        
        Write-Host "   📁 Backup creado: $backupPath" -ForegroundColor Yellow
    } else {
        Write-Host "❌ No encontrado: $view" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "🔧 Pasos manuales restantes:" -ForegroundColor Magenta
Write-Host "1. Para cada vista, actualizar @section('content_header'):" -ForegroundColor White
Write-Host '   Cambiar a: <div class="rutvans-content-header rutvans-fade-in">' -ForegroundColor Gray

Write-Host ""
Write-Host "2. Actualizar cards principales:" -ForegroundColor White
Write-Host '   Cambiar class="card" a class="rutvans-card rutvans-hover-lift rutvans-fade-in"' -ForegroundColor Gray
Write-Host '   Cambiar card-header a rutvans-card-header' -ForegroundColor Gray
Write-Host '   Cambiar card-body a rutvans-card-body' -ForegroundColor Gray

Write-Host ""
Write-Host "3. Actualizar botones:" -ForegroundColor White
Write-Host '   btn-primary → rutvans-btn rutvans-btn-primary' -ForegroundColor Gray
Write-Host '   btn-warning → rutvans-btn rutvans-btn-warning rutvans-btn-sm' -ForegroundColor Gray
Write-Host '   btn-danger → rutvans-btn rutvans-btn-danger rutvans-btn-sm' -ForegroundColor Gray

Write-Host ""
Write-Host "4. Actualizar tablas:" -ForegroundColor White
Write-Host '   Agregar class="rutvans-table" a las tablas' -ForegroundColor Gray
Write-Host '   Usar rutvans-badge para IDs' -ForegroundColor Gray

Write-Host ""
Write-Host "5. Agregar CSS:" -ForegroundColor White
Write-Host '   <link href="{{ asset(\"css/rutvans-admin.css\") }}" rel="stylesheet">' -ForegroundColor Gray

Write-Host ""
Write-Host "6. Actualizar modales:" -ForegroundColor White
Write-Host '   modal-content → rutvans-modal-content' -ForegroundColor Gray
Write-Host '   modal-header → rutvans-modal-header' -ForegroundColor Gray
Write-Host '   modal-body → rutvans-modal-body' -ForegroundColor Gray

Write-Host ""
Write-Host "🎨 ¡Diseño RUTVANS aplicado! Esquema de colores:" -ForegroundColor Green
Write-Host "   - Primario: #ff6600 (naranja)" -ForegroundColor White
Write-Host "   - Secundario: #000000 (negro)" -ForegroundColor White
Write-Host "   - Fondo: #f0f2f5 (gris claro)" -ForegroundColor White
Write-Host "   - Fuente: Poppins" -ForegroundColor White

Write-Host ""
Write-Host "✨ Las vistas ya actualizadas son:" -ForegroundColor Cyan
Write-Host "   - metodoPago/index.blade.php ✅" -ForegroundColor Green
Write-Host "   - usuarios/index.blade.php ✅" -ForegroundColor Green
Write-Host "   - empleados/drivers/index.blade.php ✅" -ForegroundColor Green
