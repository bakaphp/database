<?php

namespace Baka\Database;

/**
 * Trait to implemente everything needed from a simple CRUD in a API
 *
 */
interface CustomeFieldsInterface
{
    /**
     * Set the custom primary field id
     *
     * @param int $id
     */
    public function setCustomId(int $id);

}
