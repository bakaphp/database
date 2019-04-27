<?php

namespace Baka\Database\CustomFilters;

use Baka\Database\Model;
use Baka\Database\Contracts\HashTableTrait;

class CustomFilters extends Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var int
     */
    public $system_modules_id;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var int
     */
    public $apps_id;

    /**
     * @var int
     */
    public $companies_id;

    /**
     * @var int
     */
    public $companies_branch_id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $sequence_logic;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $total_conditions;

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
        return 'custom_filters';
    }

    /**
     * Initialize some stuff.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->hasMany('id', '\Baka\Database\CustomFilters\Conditions', 'id', ['alias' => 'conditions']);
    }
}
