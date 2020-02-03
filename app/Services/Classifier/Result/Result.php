<?php

namespace App\Services\Classifier\Result;

use App\Services\Classifier\ClassifierServiceInterface;
use Illuminate\Contracts\Support\Arrayable;

class Result implements Arrayable
{
    public $id;
    public $text;
    public $topic;
    public $source;

    /**
     * @param static|array $item
     * @return static
     */
    public static function make($item): self
    {
        $res = new static;
        if ($item instanceof static) {
            $res->id = (int)$item->id;
            $res->text = $item->text;
            $res->topic = $item->topic;
            $res->source = $item->source;
        } else if (is_array($item)) {
            $res->id = (int)$item['id'];
            $res->text = $item['text'];
            $res->topic = $item['topic'];
            $res->source = $item['source'];
        }

        return $res;
    }

    /**
     * @return bool
     */
    public function isPredicted()
    {
        return $this->source === ClassifierServiceInterface::SOURCE_PREDICTED;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'id'     => $this->id,
            'text'   => $this->text,
            'topic'  => $this->topic,
            'source' => $this->source,
        ];
    }
}
