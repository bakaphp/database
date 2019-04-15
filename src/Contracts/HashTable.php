<?php

declare(strict_types=1);

namespace Baka\Database\Contracts;

use Gewaer\Models\Users;
use Gewaer\Exception\ServerErrorHttpException;
use Gewaer\Exception\ModelException;

/**
 * Trait ResponseTrait.
 *
 * @package Gewaer\Traits
 *
 * @property Users $user
 * @property Config $config
 * @property Request $request
 * @property Auth $auth
 * @property \Phalcon\Di $di
 *
 */
trait HashTable
{
    protected $settingsModel;

    /**
     * Set the setting model.
     *
     * @return void
     */
    private function createSettingsModel(): void
    {
        $class = get_class($this) . 'Settings';

        $this->settingsModel = new $class();
    }

    /**
     * Get the primary key of this model, this will only work on model with just 1 primary key.
     *
     * @return string
     */
    private function getPrimaryKey(): string
    {
        // Get the first matching primary key.
        // @TODO This will hurt on compound primary keys.
        $metaData = new \Phalcon\Mvc\Model\MetaData\Memory();
        // Get the first matching primary key.
        // @TODO This will hurt on compound primary keys.
        return $this->getSource() . '_' . $metaData->getPrimaryKeyAttributes($this)[0];
    }

    /**
     * Set the settings.
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function set(string $key, $value) : bool
    {
        $this->createSettingsModel();

        if (!is_object($this->settingsModel)) {
            throw new ServerErrorHttpException('ModelSettingsTrait need to have a settings mdoel configure, check the model setting existe for this class' . get_class($this));
        }

        //setup the user notificatoin setting
        $this->settingsModel->{$this->getPrimaryKey()} = $this->getId();
        $this->settingsModel->name = $key;
        $this->settingsModel->value = $value;

        if (!$this->settingsModel->save()) {
            throw new ModelException((string) current($this->settingsModel->getMessages()));
        }

        return true;
    }

    /**
     * Get the settings base on the key.
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        $this->createSettingsModel();
        $value = $this->settingsModel->findFirst([
            'conditions' => "{$this->getPrimaryKey()} = ?0 and name = ?1",
            'bind' => [$this->getId(), $key]
        ]);

        if (is_object($value)) {
            return $value->value;
        }

        return null;
    }
}
