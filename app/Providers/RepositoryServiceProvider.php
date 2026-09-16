<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\RedemptionRepositoryInterface;
use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Firestore\FirestoreUserRepository;
use App\Repositories\Firestore\FirestoreTransactionRepository;
use App\Repositories\Firestore\FirestoreRedemptionRepository;
use App\Repositories\Firestore\FirestoreSettingsRepository;
use App\Repositories\Firestore\FirestoreAuditLogRepository;
use App\Services\Firebase\FirestoreClient;

/**
 * Punto único donde se enlaza cada interfaz de repositorio con su
 * implementación concreta (Firestore). Mantener esto centralizado permite
 * cambiar la fuente de datos de una entidad sin tocar los Services ni los
 * Controllers que dependen de la interfaz.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings de interfaz -> implementación
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            return new FirestoreUserRepository(
                $app->make(FirestoreClient::class),
                config('firebase.collections.users')
            );
        });

        $this->app->bind(TransactionRepositoryInterface::class, function ($app) {
            return new FirestoreTransactionRepository(
                $app->make(FirestoreClient::class),
                config('firebase.collections.transactions')
            );
        });

        $this->app->bind(RedemptionRepositoryInterface::class, function ($app) {
            return new FirestoreRedemptionRepository(
                $app->make(FirestoreClient::class),
                config('firebase.collections.redemptions')
            );
        });

        $this->app->bind(SettingsRepositoryInterface::class, function ($app) {
            return new FirestoreSettingsRepository(
                $app->make(FirestoreClient::class),
                config('firebase.collections.settings')
            );
        });

        $this->app->bind(AuditLogRepositoryInterface::class, function ($app) {
            return new FirestoreAuditLogRepository(
                $app->make(FirestoreClient::class),
                config('firebase.collections.audit_logs')
            );
        });

        $this->app->bind(\App\Repositories\Contracts\ProductRepositoryInterface::class, function ($app) {
            return new \App\Repositories\Firestore\FirestoreProductRepository(
                $app->make(FirestoreClient::class)
            );
        });

        // Soporte retrocompatible para namespace alternativo App\Contracts\Repositories
        if (!interface_exists('App\Contracts\Repositories\UserRepositoryInterface')) {
            class_alias(UserRepositoryInterface::class, 'App\Contracts\Repositories\UserRepositoryInterface');
        }
        if (!interface_exists('App\Contracts\Repositories\TransactionRepositoryInterface')) {
            class_alias(TransactionRepositoryInterface::class, 'App\Contracts\Repositories\TransactionRepositoryInterface');
        }
        if (!interface_exists('App\Contracts\Repositories\RedemptionRepositoryInterface')) {
            class_alias(RedemptionRepositoryInterface::class, 'App\Contracts\Repositories\RedemptionRepositoryInterface');
        }
        if (!interface_exists('App\Contracts\Repositories\SettingsRepositoryInterface')) {
            class_alias(SettingsRepositoryInterface::class, 'App\Contracts\Repositories\SettingsRepositoryInterface');
        }
        if (!interface_exists('App\Contracts\Repositories\AuditLogRepositoryInterface')) {
            class_alias(AuditLogRepositoryInterface::class, 'App\Contracts\Repositories\AuditLogRepositoryInterface');
        }
    }
}
