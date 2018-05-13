<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class CustomFields extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var int
     */
    public $companies_id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $modules_id;

    /**
     * @var int
     */
    public $fields_type_id;

    /**
     * Returns the name of the table associated to the model.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'custom_fields';
    }

    /**
     * Initialize some stuff.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->hasOne('fields_type_id', '\Baka\Database\CustomFields\FieldsType', 'id', ['alias' => 'type']);
    }

    /**
     * Get the felds of this custom field module
     *
     * @param string $module
     * @return void
     */
    public static function getFields(string $module)
    {
        $modules = Modules::findFirstByName($module);

        if ($modules) {
            return self::find([
                'modules_id = ?0',
                'bind' => [$modules->id],
                'columns' => 'name, label, type'
            ]);
        }

        return null;
    }
}
