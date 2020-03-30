<?php


use Phinx\Seed\AbstractSeed;
use Test\Model\Leads;

class Init extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'baka',
                'description' => 'baka',
                'created_at' => date('Y-m-d H:m:s'),
                'is_deleted'=>0
            ]
        ];

        $table = $this->table('apps');
        $table->insert($data)
              ->save();

        $data = [
            [
                'id' => 1,
                'name' => 'baka',
                'slug' => 'baka',
                'model_name' => Leads::class,
                'menu_order' => 1,
                'browse_fields' => '{}',
                'apps_id' => 1,
                'parents_id' => 0,
                'use_elastic' => 1,
                'created_at' => date('Y-m-d H:m:s'),
                'is_deleted'=>0
            ]
        ];

        $table = $this->table('system_modules');
        $table->insert($data)
              ->save();

        $data = [
            [
                'id' => 1,
                'name' => 'Max',
                'system_modules_id' => 1,
                'apps_id' => 1,
                'companies_id' => 1,
                'companies_branch_id' => 1,
                'users_id' => 1,
                'sequence_logic' => 1,
                'description' => 'baka',
                'criterias' => '',
                'created_at' => date('Y-m-d H:m:s'),
                'is_deleted'=>0
            ]
        ];

        $table = $this->table('leads');
        $table->insert($data)
              ->save();
    }
}
