<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductCatalogSeeder::class);
        $this->call(VietnamPurifierCatalogSeeder::class);

        if ($this->container->environment(['local', 'development'])) {
            $this->call(AdminCrmDemoSeeder::class);
        }
    }
}
