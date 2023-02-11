<?php

namespace App\Application;

use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use ReturnTypeWillChange;

class SerializableCollection implements JsonSerializable
{
    private $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $elements = [];
        foreach ($this->collection as $element) {
            $elements[] = json_decode(json_encode($element), true);
        }

        return $elements;
    }
}
