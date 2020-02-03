<?php

namespace App\Services\Importer;

use App\Models\User;

interface ImporterInterface
{
    /**
     * ImporterInterface constructor.
     *
     * @param \App\Models\User $user
     */
    public function __construct(User $user);

    /**
     * @param \GuzzleHttp\Psr7\UploadedFile|\Illuminate\Http\UploadedFile|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return static
     */
    public function setFile($file);

    /**
     * @return int count imported phrases
     */
    public function import();
}
