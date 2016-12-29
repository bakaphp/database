<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class CustomFields extends Model
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
    public $business_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $modules_id;

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
        return 'custom_fields';
    }

}
