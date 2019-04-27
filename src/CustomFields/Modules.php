<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class Modules extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $apps_id;

    /**
     * @var string
     */
    public $model_name;

    /**
     * @var string
     */
    public $name;

    /**
     * Returns the name of the table associated to the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'custom_fields_modules';
    }
}
