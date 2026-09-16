<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Contract\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Encapsula subidas/lecturas de Firebase Storage (usado por ejemplo para
 * el logo del sistema en Configuración, o fotos de perfil administradas
 * desde el panel).
 */
class FirebaseStorageService
{
    public function __construct(private readonly Storage $storage)
    {
    }

    public function upload(UploadedFile $file, string $path): string
    {
        $bucket = $this->storage->getBucket();

        $object = $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            ['name' => $path]
        );

        return $object->signedUrl(new \DateTime('+10 years'));
    }

    public function delete(string $path): void
    {
        $bucket = $this->storage->getBucket();
        $object = $bucket->object($path);

        if ($object->exists()) {
            $object->delete();
        }
    }
}
