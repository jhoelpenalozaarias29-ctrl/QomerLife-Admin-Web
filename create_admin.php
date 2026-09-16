<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Kreait\Firebase\Contract\Auth;
use App\Services\Firebase\FirestoreClient;

$auth = app(Auth::class);
$firestore = app(FirestoreClient::class);

$email = 'admin@qomer.com';
$password = 'admin123456';

echo "Creando administrador $email...\n";

try {
    // 1. Intentar encontrar al usuario en Firebase Auth por email
    try {
        $firebaseUser = $auth->getUserByEmail($email);
        echo "El usuario Auth ya existe. Actualizando contraseña...\n";
        $auth->changeUserPassword($firebaseUser->uid, $password);
        $uid = $firebaseUser->uid;
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
        // 2. Si no existe, crearlo
        echo "El usuario Auth no existe. Creando...\n";
        $userProperties = [
            'email' => $email,
            'emailVerified' => true,
            'password' => $password,
            'displayName' => 'Administrador Qomer',
            'disabled' => false,
        ];
        $createdUser = $auth->createUser($userProperties);
        $uid = $createdUser->uid;
    }

    echo "UID del admin: $uid\n";

    // 3. Crear/Actualizar documento en Firestore
    $usersCollection = config('firebase.collections.users', 'users');
    $docRef = $firestore->document($usersCollection, $uid);
    
    $adminData = [
        'name' => 'Administrador Qomer',
        'email' => $email,
        'role' => 'ADMIN',
        'status' => 'ACTIVE',
        'ecoPoints' => 0,
        'ecoLevel' => 1,
        'createdAt' => new \DateTimeImmutable(),
    ];

    $docRef->set($adminData, ['merge' => true]);

    echo "¡Administrador creado con éxito en Firestore!\n";
    echo "Correo: $email\n";
    echo "Contraseña: $password\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
