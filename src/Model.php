<?php

namespace Baka\Database;

use Phalcon\Mvc\Model\ResultsetInterface;

class Model extends \Phalcon\Mvc\Model
{
    /**
     * @return int
     */
    public $id;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var int
     */
    public $is_deleted = 0;

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
        $this->updated_at = null;
        $this->is_deleted = 0;
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
    public function softDelete()
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
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Content
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Since Phalcon 3, they pass model objet throught the toArray function when we call json_encode, that can fuck u up, if you modify the obj
     * so we need a way to convert it to array without loosing all the extra info we add
     *
     * @return array
     */
    public function toFullArray(): array
    {
        //convert the obj to array in order to conver to json
        $result = get_object_vars($this);

        foreach ($result as $key => $value) {
            if (preg_match('#^_#', $key) === 1) {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
