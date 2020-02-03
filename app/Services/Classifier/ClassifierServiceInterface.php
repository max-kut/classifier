<?php

namespace App\Services\Classifier;

use App\Services\Classifier\Result\ResultCollection;
use Illuminate\Support\Collection;

interface ClassifierServiceInterface
{
    const SOURCE_TRAINED = 'trained';
    const SOURCE_PREDICTED = 'predicted';

    /**
     * @param \Illuminate\Support\Collection|\App\Models\Phrase[] $phrases
     * @return \App\Services\Classifier\Result\ResultCollection|\App\Services\Classifier\Result\Result[] response from external calculating service
     */
    public function calculate(Collection $phrases): ResultCollection;
}
