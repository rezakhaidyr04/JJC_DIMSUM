<?php

namespace App\Models\Traits;

trait HasCustomId
{
    /**
     * Allow access to the model primary key via the "id" attribute.
     */
    public function getIdAttribute()
    {
        return $this->getKey();
    }
}
