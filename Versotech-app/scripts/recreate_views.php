<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Inclui e executa a migration que cria as views (arquivo retorna a instância da classe anônima)
$migration = include __DIR__ . '/../database/migrations/2025_10_25_123236_create_processed_views.php';
if (is_object($migration) && method_exists($migration, 'up')) {
    echo "Recriando views (incluindo inativos)...\n";
    $migration->up();
    echo "Views recriadas com sucesso.\n";
} else {
    echo "Não foi possível carregar a migration das views.\n";
}
