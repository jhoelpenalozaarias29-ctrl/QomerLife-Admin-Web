<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Kreait\Firebase\Contract\Auth;
use App\Services\Firebase\FirestoreClient;



Artisan::command('admin:create {email=admin@qomerlife.com} {password=Admin123456*!}', function ($email, $password) {
    $this->info("Creando o actualizando usuario admin: {$email}");

    $auth = app(Auth::class);
    $firestore = app(FirestoreClient::class);

    try {
        $userRecord = $auth->getUserByEmail($email);
        $this->info("El usuario ya existe en Firebase Auth (UID: {$userRecord->uid}). Actualizando contraseña...");
        $auth->changeUserPassword($userRecord->uid, $password);
    } catch (\Kreait\Firebase\Exception\Auth\UserNotFound) {
        $userRecord = $auth->createUser([
            'email' => $email,
            'password' => $password,
            'displayName' => 'Administrador Qomer',
            'emailVerified' => true,
        ]);
        $this->info("Usuario creado en Firebase Auth con UID: {$userRecord->uid}");
    } catch (\Throwable $e) {
        $this->error("Error en Firebase Auth: " . $e->getMessage());
        return 1;
    }

    try {
        $firestore->collection('users')->document($userRecord->uid)->set([
            'name' => 'Administrador Qomer',
            'email' => $email,
            'role' => 'ADMIN',
            'status' => 'ACTIVE',
            'createdAt' => new \DateTimeImmutable(),
            'lastLogin' => new \DateTimeImmutable(),
        ]);
        $this->info("Documento de usuario ADMIN registrado exitosamente en Firestore [users/{$userRecord->uid}].");
    } catch (\Throwable $e) {
        $this->error("Error en Firestore: " . $e->getMessage());
        return 1;
    }

    $this->info("¡Credenciales listas!");
    $this->table(['Email', 'Password', 'Role'], [[$email, $password, 'ADMIN']]);
    return 0;
})->purpose('Crea o restablece un usuario Administrador en Firebase Auth y Firestore');

