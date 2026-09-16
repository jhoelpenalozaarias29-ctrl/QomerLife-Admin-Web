<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Diagnostic: Firestore Data Check ===\n\n";

// Test users
$userRepo = app(\App\Repositories\Contracts\UserRepositoryInterface::class);
echo "Users count: " . $userRepo->count() . "\n";

// Test transactions
$txRepo = app(\App\Repositories\Contracts\TransactionRepositoryInterface::class);
$txAll = $txRepo->all();
echo "Transactions count (from all()): " . count($txAll) . "\n";
echo "Transactions count (from count()): " . $txRepo->count() . "\n";
if (count($txAll) > 0) {
    echo "First transaction keys: " . implode(', ', array_keys($txAll[0])) . "\n";
    echo "EcoPoints field sample: " . var_export($txAll[0]['ecoPoints'] ?? 'MISSING', true) . "\n";
}
echo "Sum ecoPoints: " . $txRepo->sumField('ecoPoints') . "\n";

// Test redemptions
$redemRepo = app(\App\Repositories\Contracts\RedemptionRepositoryInterface::class);
$redemAll = $redemRepo->all();
echo "Redemptions count (from all()): " . count($redemAll) . "\n";
echo "Redemptions count (from count()): " . $redemRepo->count() . "\n";
if (count($redemAll) > 0) {
    echo "First redemption keys: " . implode(', ', array_keys($redemAll[0])) . "\n";
}
