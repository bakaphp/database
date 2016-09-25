<?php

namespace Baka\Database;

use Phalcon\Mvc\Model\ResultsetInterface;

class Model extends \Phalcon\Mvc\Model
{
    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * Get the primary id of this model
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * before validate create
     *
     * @return void
     */
    public function beforeValidationOnCreate()
    {
        $this->beforeCreate();
    }

    /**
     * before validate update
     *
     * @return void
     */
    public function beforeValidationOnUpdate()
    {
        $this->beforeUpdate();
    }

    /**
     * Before create
     *
     * @return void
     */
    public function beforeCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = '0000-00:00 00:00:00';

    }

    /**
     * Before update
     *
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * Soft Delete
     *
     * @return void
     */
    public function softDelete(): boolean
    {
        $this->is_deleted = 1;

        return $this->save();
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Content[]
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Content
     */
    public static function findFirst($parameters = null): \Phalcon\Mvc\Model
    {
        return parent::findFirst($parameters);
    }
}
