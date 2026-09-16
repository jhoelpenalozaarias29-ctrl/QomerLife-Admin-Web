<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$txRepo = app(\App\Repositories\Contracts\TransactionRepositoryInterface::class);
$all = $txRepo->all();

echo "=== COLECCIÓN: transactions (" . count($all) . " docs) ===\n";
if (!empty($all)) {
    $sample = $all[0];
    // Serializar timestamps a string para JSON
    $serialized = array_map(function($v) {
        if (is_object($v) && method_exists($v, 'get')) return $v->get()->format('c');
        if ($v instanceof \DateTimeInterface) return $v->format('c');
        return $v;
    }, $sample);
    echo json_encode($serialized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}

$redemRepo = app(\App\Repositories\Contracts\RedemptionRepositoryInterface::class);
$all2 = $redemRepo->all();
echo "\n=== COLECCIÓN: redemptions (" . count($all2) . " docs) ===\n";
if (!empty($all2)) {
    $sample = $all2[0];
    $serialized = array_map(function($v) {
        if (is_object($v) && method_exists($v, 'get')) return $v->get()->format('c');
        if ($v instanceof \DateTimeInterface) return $v->format('c');
        return $v;
    }, $sample);
    echo json_encode($serialized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}

$userRepo = app(\App\Repositories\Contracts\UserRepositoryInterface::class);
$users = $userRepo->all();
echo "\n=== COLECCIÓN: users (" . count($users) . " docs) ===\n";
if (!empty($users)) {
    $u = $users[0];
    $attrSerialized = array_map(function($v) {
        if (is_object($v) && method_exists($v, 'get')) return $v->get()->format('c');
        if ($v instanceof \DateTimeInterface) return $v->format('c');
        return $v;
    }, $u->attributes);
    echo json_encode([
        'uid'   => $u->uid,
        'name'  => $u->name,
        'email' => $u->email,
        'role'  => $u->role,
        'status' => $u->status,
        'lastLogin' => $u->lastLogin ? $u->lastLogin->format('c') : null,
        'attributes' => $attrSerialized,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}
