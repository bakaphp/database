<?php

namespace Baka\Database;

use Baka\Database\Exception\ModelNotFoundException;
use Baka\Database\Exception\ModelNotProcessedException;
use Phalcon\Mvc\Model\MetaData\Memory as MetaDataMemory;
use RuntimeException;

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
     * Get the primary id of this model.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * before validate create.
     *
     * @return void
     */
    public function beforeValidationOnCreate()
    {
        $this->beforeCreate();
    }

    /**
     * before validate update.
     *
     * @return void
     */
    public function beforeValidationOnUpdate()
    {
        $this->beforeUpdate();
    }

    /**
     * Before create.
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
     * Before update.
     *
     * @return void
     */
    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * Soft Delete.
     *
     * @return void
     */
    public function softDelete()
    {
        $this->is_deleted = 1;

        return $this->save();
    }

    /**
     * Get by Id or thrown an exceptoin.
     *
     * @param mixed $id
     * @return self
     */
    public static function getByIdOrFail($id): self
    {
        $record = static::findFirst([
            'conditions' => 'id = ?0 and is_deleted = ?1',
            'bind' => [$id, 0]
        ]);

        if ($record) {
            return $record;
        }

        throw new ModelNotFoundException('Record not found');
    }

    /**
    * save model or throw an exception.
    *
    * @param null|mixed $data
    * @param null|mixed $whiteList
    */
    public function saveOrFail($data = null, $whiteList = null): bool
    {
        if ($savedModel = static::save($data, $whiteList)) {
            return $savedModel;
        }

        $this->throwErrorMessages();
    }

    /**
    * update model or throw an exception.
    *
    * @param null|mixed $data
    * @param null|mixed $whiteList
    */
    public function updateOrFail($data = null, $whiteList = null): bool
    {
        if ($updatedModel = static::update($data, $whiteList)) {
            return $updatedModel;
        }

        $this->throwErrorMessages();
    }

    /**
    * Delete the model or throw an exception.
    */
    public function deleteOrFail(): bool
    {
        if (!parent::delete()) {
            $this->throwErrorMessages();
        }

        return true;
    }

    /**
     * Since Phalcon 3, they pass model objet throught the toArray function when we call json_encode, that can fuck u up, if you modify the obj
     * so we need a way to convert it to array without loosing all the extra info we add.
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

    /**
     * Get the list of primary keys from the current model.
     *
     * @return array
     */
    protected function getPrimaryKeys(): array
    {
        $metaData = new MetaDataMemory();
        return $metaData->getPrimaryKeyAttributes($this);
    }

    /**
     * Get get the primarey key, if we have more than 1 , use keys.
     *
     * @return array
     */
    protected function getPrimaryKey(): string
    {
        $primaryKeys = $this->getPrimaryKeys();

        if (empty($primaryKeys)) {
            throw new RuntimeException('No primary key defined in this table ' . $this->getSource());
        }

        return $primaryKeys[0];
    }

    /**
    * Throws an exception with including all validation messages that were retrieved.
    * @throws ModelNotProcessedException
    */
    protected function throwErrorMessages(): void
    {
        throw new ModelNotProcessedException(
            current($this->getMessages())->getMessage()
        );
    }
}
