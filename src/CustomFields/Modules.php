<?php

namespace Baka\Database\CustomFields;

use Baka\Database\Model;

class Modules extends Model
{
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
        return 'modules';
    }
}
