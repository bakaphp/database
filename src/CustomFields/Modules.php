<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class Modules extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

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
        return 'modules';
    }

}
