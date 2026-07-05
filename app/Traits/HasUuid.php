<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the trait.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getUuidColumnName()})) {
                $model->{$model->getUuidColumnName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->getUuidColumnName();
    }

    /**
     * Get the name of the UUID column.
     *
     * @return string
     */
    public function getUuidColumnName()
    {
        return 'uuid';
    }
}
