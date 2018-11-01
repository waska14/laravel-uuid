<?php

namespace Waska\LaravelUuid\Traits;

use Waska\Uuid;

trait UuidTrait
{

    /**
     * Override model save method.
     *
     * This method get uuid_columns from model or uses default "uuid" string and for each uuid column name
     * generates uuid string and calls parent save method.
     *
     * @param array $options
     *
     * @return void
     */
    public function save(array $options = [])
    {
        $uuid_columns = property_exists($this, "uuid_column") ? $this->uuid_column : "uuid";
        foreach ((array) $uuid_columns as $column_name) {
            if (in_array($column_name, $this->fillable) and !$this->$column_name) {
                $this->setUuid($column_name);
            }
        }
        parent::save($options);
    }

    /**
     * This protected method generates uuid and set it as the given column value.
     *
     * @param string $column_name. Column to set uuid.
     *
     * @return void
     */
    protected function setUuid($column_name)
    {
        do {
            $this->$column_name = Uuid::get(4);
        } while (self::where($column_name, $this->$column_name)->count() > 0);
    }
}
