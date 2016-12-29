# Baka Database

Baka Database

# Model

The default behaviero of the baka model is giving you the normal functions to work with any mc project

- automatic updated_at , created_at  times
- soft delete by just calling softDelete() insted of delete()
- toFullArray() instead of toArray() to avoid dynamic fields on your model been removed by phalcon

# Custom Fields Model

One of the things we look for is a table that growth in a vertical way instead of horizontal . We made custom fields to avoid having to go later on in proyect and having to add new fields to the table, with this we can managed them dynamicly and later on add UI for the client to better manage the info

To use you need your  model to extend from ModelCustomFields

```php
<?php

namespace Mesocom\Models;

class Leads extends \Baka\Database\ModelCustomFields
{
}
```

And you also need to create the custom fields model value

```php
<?php

namespace Mesocom\Models;

use \Baka\Database\CustomeFieldsInterface;

class LeadsCustomFields extends Baka implements CustomeFieldsInterface
{
   /**
     * Set the custom primary field id
     *
     * @param int $id
     */
    public function setCustomId(int $id)
    {
        $this->leads_id = $id;
    }
}
```

Thats it now you can use this custom fields model like any other, no other explication is needed they will work like any phalcon normal model