<?php

/**
 * Configuración centralizada de Firebase.
 *
 * Todos los servicios (Firestore, Auth, Storage) se resuelven a partir
 * de este archivo. Ningún servicio del proyecto debe instanciar el SDK
 * de Firebase directamente: siempre pasa por
 * App\Providers\FirebaseServiceProvider.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cuenta de servicio
    |--------------------------------------------------------------------------
    | Ruta (relativa a storage_path o absoluta) al JSON de credenciales
    | descargado desde Firebase Console > Configuración del proyecto >
    | Cuentas de servicio.
    */
    'credentials' => (static function () {
        // El valor de FIREBASE_CREDENTIALS puede ser absoluto (ej. "C:\...", "/...")
        // o relativo a storage_path() (ej. "app/firebase/service-account.json").
        $path = env('FIREBASE_CREDENTIALS', 'app/firebase/service-account.json');

        if ($path !== null && ! str_starts_with($path, '/') && ! preg_match('/^[A-Za-z]:[\\\\\\/]/', $path)) {
            $path = storage_path($path);
        }

        return $path;
    })(),

    'project_id' => env('FIREBASE_PROJECT_ID'),

    'database_url' => env('FIREBASE_DATABASE_URL'),

    'storage' => [
        'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Web API Key
    |--------------------------------------------------------------------------
    | Necesaria para el flujo de login (signInWithPassword vía REST de
    | Identity Toolkit). Es distinta de la cuenta de servicio: esta se
    | considera pública (se usa también en la app móvil / frontend).
    */
    'web_api_key' => env('FIREBASE_WEB_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Nombres de colecciones Firestore
    |--------------------------------------------------------------------------
    | Centralizados aquí para que un cambio de nombre de colección no
    | obligue a tocar código disperso por todo el proyecto.
    */
    'collections' => [
        'users' => 'users',
        'transactions' => 'transactions',
        'redemptions' => 'redemptions',
        'machines' => 'machines',
        'settings' => 'settings',
        'audit_logs' => 'auditLogs',
        'rewards' => 'rewards',
        'notifications' => 'notifications',
        'analytics' => 'analytics', // agregados precalculados (Fase 3)
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache de estadísticas
    |--------------------------------------------------------------------------
    */
    'stats_cache_ttl' => (int) env('STATS_CACHE_TTL_MINUTES', 5),
];
