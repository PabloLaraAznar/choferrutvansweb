<?php

/**
 * Script de prueba para verificar que las vistas de empleados se pueden cargar sin errores.
 */

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

$app = app();

try {
    echo "🧪 Probando vistas de empleados...\n\n";

    // Test drivers index
    echo "📋 Probando empleados/drivers/index.blade.php... ";
    $view = view('empleados.drivers.index');
    echo "✅ OK\n";

    // Test coordinates index  
    echo "📋 Probando empleados/coordinates/index.blade.php... ";
    $view = view('empleados.coordinates.index');
    echo "✅ OK\n";

    // Test cashiers index
    echo "📋 Probando empleados/cashiers/index.blade.php... ";
    $view = view('empleados.cashiers.index');
    echo "✅ OK\n";

    echo "\n✨ Todas las vistas de empleados se cargan correctamente!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . " línea " . $e->getLine() . "\n";
    exit(1);
}
