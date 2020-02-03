<?php

namespace App\Services\Classifier\Result;

use Illuminate\Support\Collection;

final class ResultCollection extends Collection
{
    /**
     * ResultCollection constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $this->items = array_map(function($item){
            return Result::make($item);
        }, $this->getArrayableItems($items));
    }
}
