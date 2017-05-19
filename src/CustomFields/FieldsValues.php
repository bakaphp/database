<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class FieldsValues extends Model
{
    /**
     * @var int
     */
    public $custom_fields_id;

    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * Returns the name of the table associated to the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'fields_values';
    }
}
