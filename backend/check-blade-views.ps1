# Script para verificar integridad de vistas Blade
# Detecta errores comunes en las directivas @section/@endsection

$vistasCrud = @(
    "resources\views\empleados\drivers\index.blade.php",
    "resources\views\empleados\coordinates\index.blade.php", 
    "resources\views\empleados\cashiers\index.blade.php",
    "resources\views\usuarios\index.blade.php",
    "resources\views\metodoPago\index.blade.php",
    "resources\views\rutas\index.blade.php",
    "resources\views\units\index.blade.php",
    "resources\views\horarios\index.blade.php"
)

function Test-BladeView {
    param($filePath)
    
    if (-not (Test-Path $filePath)) {
        Write-Warning "❌ No existe: $filePath"
        return $false
    }
    
    $content = Get-Content $filePath -Raw
    
    # Verificar balance de @section/@endsection
    $sectionCount = ([regex]::Matches($content, '@section\(') | Measure-Object).Count
    $endsectionCount = ([regex]::Matches($content, '@endsection') | Measure-Object).Count
    
    if ($sectionCount -ne $endsectionCount) {
        Write-Warning "❌ $filePath - Secciones desbalanceadas: $sectionCount @section vs $endsectionCount @endsection"
        return $false
    }
    
    # Verificar que extienda AdminLTE
    if ($content -notmatch "@extends\('adminlte::page'\)") {
        Write-Warning "❌ $filePath - No extiende 'adminlte::page'"
        return $false
    }
    
    # Verificar que incluya rutvans-admin.css
    if ($content -notmatch "rutvans-admin\.css") {
        Write-Warning "⚠️  $filePath - No incluye rutvans-admin.css"
    }
    
    Write-Host "✅ $filePath - Estructura correcta" -ForegroundColor Green
    return $true
}

Write-Host "`n🔍 VERIFICANDO INTEGRIDAD DE VISTAS BLADE RUTVANS`n" -ForegroundColor Cyan

$totalVistas = $vistasCrud.Count
$vistasCorrectas = 0

foreach ($vista in $vistasCrud) {
    if (Test-BladeView $vista) {
        $vistasCorrectas++
    }
}

Write-Host "`n📊 RESUMEN:" -ForegroundColor Yellow
Write-Host "✅ Vistas correctas: $vistasCorrectas/$totalVistas" -ForegroundColor Green

if ($vistasCorrectas -eq $totalVistas) {
    Write-Host "🎉 ¡Todas las vistas están correctas!" -ForegroundColor Green
} else {
    Write-Host "⚠️  Algunas vistas necesitan corrección" -ForegroundColor Yellow
}

Write-Host "`n📋 Para verificar errores en tiempo real:" -ForegroundColor Cyan
Write-Host "php artisan view:clear" -ForegroundColor Gray
Write-Host "php artisan route:list" -ForegroundColor Gray
