<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class FieldsValues extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $custom_fields_id;

    /**
     *
     * @var string
     */
    public $value;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    public function getSource()
    {
        return 'fields_values';
    }

}
