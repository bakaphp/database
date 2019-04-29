<?php

namespace Test\Model;

use Baka\Database\Model;
use Baka\Database\Contracts\CustomFields\CustomFieldsTrait;

class LeadsNormal extends Model
{
    
    /**
     * Specify the table.
     *
     * @return void
     */
    public function getSource()
    {
        return 'leads';
    }
}
