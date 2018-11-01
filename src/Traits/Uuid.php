<?php

namespace Waska\LaravelUuid\Traits;

use Waska\Uuid;

trait UuidTrait
{

    /**
     * Override model save method.
     *
     * This method get uuid_columns and for each uuid column name
     * generates uuid string and calls parent save method.
     *
     * @param array $options
     *
     * @return void
     */
    public function save(array $options = [])
    {
        foreach ($this->getUuidColumns() as $column_name) {
            $this->setUuid($column_name);
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

    /**
     * This private method returns array of uuid columns which must be filled with unique identificator
     *
     * @return array
     */
    private function getUuidColumns()
    {
        $uuid_columns = (array) (property_exists($this, "uuid_column") ? $this->uuid_column : config('waska.uuid.default_column_name'));
        $uuid_fillable_columns       = [];
        foreach ($uuid_columns as $column_name) {
            if (!$this->$column_name and ($column_name == $this->primaryKey or in_array($column_name, $this->fillable))) {
                $uuid_fillable_columns[] = $column_name;
            }
        }
        return $uuid_fillable_columns;
    }
}
