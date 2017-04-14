<?php

namespace Baka\Database;

use Exception;

/**
 * Table with the structur of a hastable
 *  id
 *  idenfier
 *  Keys
 *  value
 *
 * Example:
 *
 * $appSettings = new AppsSettings(1);
 * $appSettings->set('pradaGenerated', 1);
 *
 * @$appSettings->get('pradaGenerated');
 */
class HashTable extends Model
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
    public $key;

    /**
     *
     * @var string
     */
    public $value;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $created_date;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $updated_date;

    /**
     *
     * @var string $app
     */
    public $settingsKey;

    /**
     * Set the current hash id to work with the obj
     *
     * @param  mixed $app
     * @return void
     */
    public function onConstruct($app)
    {
        //if not null
        if (!is_null($app)) {
            $key = $this->settingsKey;
            $this->$key = $app;
        }
    }

    /**
     * get the current setting id
     *
     * @return string
     */
    public function getSettingsKey(): string
    {
        $key = $this->settingsKey;

        if (empty($this->$key)) {
            throw new Exception("No settings key is set on the constructor");
        }

        return $this->$key;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        //return 'apps_settings';
    }

    /**
     * Get the vehicle settings
     *
     * @param  Vehicle $vehicle [description]
     * @param  string  $key     [description]
     * @return mixed
     */
    public function get(string $key)
    {
        $hash =  self::findFirst([
            'conditions' => "{$this->settingsKey} = ?0 and key = ?1",
            'bind' => [$this->getSettingsKey(), $key]
        ]);

        if ($hash) {
            return $hash->value;
        }

        return null;
    }

    /**
     * Set the vehicle key
     * @param Vehicle $vehicle [description]
     * @param string  $key     [description]
     * @param string  $value   [description]
     */
    public function set(string $key, string $value) : self
    {
        if (!$setting = $this->get($key)) {
            $setting = new self($this->getSettingsKey());
        }

        $setting->key = $key;
        $setting->value = $value;

        if (!$setting->save()) {
            throw new Exception(current($setting->getMessages()));
        }

        return $setting;
    }
}
