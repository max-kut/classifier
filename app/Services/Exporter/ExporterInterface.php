<?php

namespace App\Services\Exporter;

use App\Models\User;
use Illuminate\Http\Response;

interface ExporterInterface
{
    /**
     * @param \App\Models\User $user
     * @return static
     */
    public function setUser(User $user);

    /**
     * @return \Illuminate\Http\Response
     */
    public function export(): Response;
}
