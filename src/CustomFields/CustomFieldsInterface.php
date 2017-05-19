<?php

namespace Baka\Database\CustomFields;

/**
 * Trait to implemente everything needed from a simple CRUD in a API
 *
 */
interface CustomFieldsInterface
{
    /**
     * Set the custom primary field id
     *
     * @param int $id
     */
    public function setCustomId(int $id);
}
