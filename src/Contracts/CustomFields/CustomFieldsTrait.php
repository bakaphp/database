<?php

namespace Baka\Database\Contracts\CustomFields;

use Baka\Database\CustomFields\Modules;
use Baka\Database\CustomFields\CustomFields;
use Exception;
use ReflectionClass;

/**
 * Custom field class.
 */
trait CustomFieldsTrait
{
    protected $customFields = [];

    /**
     * Get a models custom fields.
     *
     * @param mixed $findResults
     *
     * @return Array
     *
     * @TODO: Made a change to return a single record when $getById is true. Keeping an eye on it.
     */
    public static function getCustomFields($findResults, $getById = false)
    {
        if (is_array($findResults)) {
            if (count($findResults) == 1 && $getById) {
                $findResults = [$findResults];
            }
        }

        $results = [];

        $classReflection = (new ReflectionClass($findResults[0]));
        $className = $classReflection->getShortName();
        $classNamespace = $classReflection->getNamespaceName();

        $module = Modules::findFirstByName($className);

        if ($module) {
            $model = $classNamespace . '\\' . ucfirst($module->name) . 'CustomFields';
            $modelId = strtolower($module->name) . '_id';

            $customFields = CustomFields::findByModulesId($module->id);

            if (is_array($findResults)) {
                $findResults[0] = $findResults[0]->toArray();
            } else {
                $findResults = $findResults->toArray();
            }
            foreach ($findResults as $result) {
                foreach ($customFields as $customField) {
                    $result['custom_fields'][$customField->name] = '';
                    $values = [];
                    $moduleValues = $model::find([
                        $modelId . ' = ?0 AND custom_fields_id = ?1',
                        'bind' => [$result['id'], $customField->id]
                    ]);

                    if ($moduleValues->count()) {
                        $result['custom_fields'][$customField->name] = $moduleValues[0]->value;
                    }
                }

                $results[] = $result;
            }

            return $getById ? $results[0] : $results;
        }

        return $getById ? $findResults[0]->toArray() : $findResults;
    }

    /**
     * Get all custom fields of the given object.
     *
     * @param  array  $fields
     * @return Phalcon\Mvc\Model
     */
    public function getAllCustomFields(array $fields = [])
    {
        if (!$models = Modules::findFirstByName($this->getSource())) {
            return;
        }

        $conditions = [];
        $fieldsIn = null;

        if (!empty($fields)) {
            $fieldsIn = " and name in ('" . implode("','", $fields) . ')';
        }

        $conditions = 'modules_id = ? ' . $fieldsIn;

        $bind = [$this->getId(), $models->getId()];

        $customFieldsValueTable = $this->getSource() . '_custom_fields';

        $result = $this->getReadConnection()->prepare("SELECT l.{$this->getSource()}_id,
                                               c.id as field_id,
                                               c.name,
                                               l.value ,
                                               c.users_id,
                                               l.created_at,
                                               l.updated_at
                                        FROM {$customFieldsValueTable} l,
                                             custom_fields c
                                        WHERE c.id = l.custom_fields_id
                                          AND l.leads_id = ?
                                          AND c.modules_id = ? ");

        $result->execute($bind);

        // $listOfCustomFields = $result->fetchAll();
        $listOfCustomFields = [];

        while ($row = $result->fetch(\PDO::FETCH_OBJ)) {
            $listOfCustomFields[$row->name] = $row->value;
        }

        return $listOfCustomFields;
    }

    /**
     * Allows to query a set of records that match the specified conditions.
     *
     * @param mixed $parameters
     * @return Content[]
     */
    public static function find($parameters = null)
    {
        $results = parent::find($parameters);
        $newResult = [];

        if ($results) {
            foreach ($results as $result) {
                $customFields = $result->getAllCustomFields();
                if (is_array($customFields)) {
                    //field the object
                    foreach ($customFields as $key => $value) {
                        $result->{$key} = $value;
                    }

                    $newResult[] = $result;
                }
            }

            unset($results);
        }
        return $newResult;
    }

    /**
     * Allows to query the first record that match the specified conditions.
     *
     * @param mixed $parameters
     * @return Content
     */
    public static function findFirst($parameters = null)
    {
        $result = parent::findFirst($parameters);

        if ($result) {
            $customFields = $result->getAllCustomFields();

            if (is_array($customFields)) {
                //field the object
                foreach ($result->getAllCustomFields() as $key => $value) {
                    $result->{$key} = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Create new custom fields.
     *
     * We never update any custom fields, we delete them and create them again, thats why we call cleanCustomFields before updates
     *
     * @return void
     */
    protected function saveCustomFields(): bool
    {
        //find the custom field module
        if (!$module = Modules::findFirstByName($this->getSource())) {
            return false;
        }

        //we need a new instane to avoid overwrite
        $reflector = new ReflectionClass($this);
        $classNameWithNameSpace = $reflector->getNamespaceName() . '\\' . $reflector->getShortName() . 'CustomFields';

        //if all is good now lets get the custom fields and save them
        foreach ($this->customFields as $key => $value) {
            //create a new obj per itration to se can save new info
            $customModel = new $classNameWithNameSpace();

            //validate the custome field by it model
            if ($customField = CustomFields::findFirst(['conditions' => 'name = ?0 and modules_id = ?1', 'bind' => [$key, $module->id]])) {
                //throw new Exception("this custom field doesnt exist");

                $customModel->setCustomId($this->getId());
                $customModel->custom_fields_id = $customField->id;
                $customModel->value = $value;
                $customModel->created_at = date('Y-m-d H:i:s');

                if (!$customModel->save()) {
                    throw new Exception('Custome ' . $key . ' - ' . $this->customModel->getMessages()[0]);
                }
            }
        }

        //clean
        unset($this->customFields);

        return true;
    }

    /**
     * Remove all the custom fields from the entity.
     *
     * @param  int $id
     * @return \Phalcon\MVC\Models
     */
    public function cleanCustomFields(int $id): bool
    {
        $reflector = new ReflectionClass($this);
        $classNameWithNameSpace = $reflector->getNamespaceName() . '\\' . $reflector->getShortName() . 'CustomFields';
        $customModel = new $classNameWithNameSpace();

        //return $customModel->find(['conditions' => $this->getSource() . '_id = ?0', 'bind' => [$id]])->delete();
        //we need to run the query since we dont have primary key
        $result = $this->getReadConnection()->prepare("DELETE FROM {$customModel->getSource()} WHERE " . $this->getSource() . '_id = ?');
        return $result->execute([$id]);
    }

    /**
     * Before create.
     *
     * @return void
     */
    public function beforeCreate()
    {
        if (empty($this->customFields)) {
            throw new Exception('This is a custom field module, which means it needs its custom field values in order to work, please call setCustomFields');
        }

        parent::beforeCreate();
    }

    /**
     * Before update.
     *
     * @return void
     */
    public function beforeUpdate()
    {
        parent::beforeUpdate();
    }

    /**
     * Set the custom field to update a custom field module.
     *
     * @param array $fields [description]
     */
    public function setCustomFields(array $fields)
    {
        $this->customFields = $fields;
    }

    /**
     * After the module was created we need to add it custom fields.
     *
     * @return  void
     */
    public function afterCreate()
    {
        $this->saveCustomFields();
    }

    /**
     * After save.
     * @return void
     */
    public function afterSave()
    {
    }

    /**
     * After the model was update we need to update its custom fields.
     *
     * @return void
     */
    public function afterUpdate()
    {
        //only clean and change custom fields if they are been sent
        if (!empty($this->customFields)) {
            //replace old custom with new
            $allCustomFields = $this->getAllCustomFields();
            if (is_array($allCustomFields)) {
                foreach ($this->customFields as $key => $value) {
                    $allCustomFields[$key] = $value;
                }
            }

            //set
            $this->setCustomFields($allCustomFields);
            //clean old
            $this->cleanCustomFields($this->getId());
            //save new
            $this->saveCustomFields();
        }
    }

    /**
     * After delete remove the custom fields.
     *
     * @return void
     */
    public function afterDelete()
    {
        $this->cleanCustomFields($this->getId());
    }
}
