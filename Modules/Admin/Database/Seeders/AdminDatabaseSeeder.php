<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Entities\Attribute;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();

        DB::table('admins')->insert([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@shop.com',
            'password' => bcrypt('admin123')
        ]);

        Attribute::insert([
            ['name' => 'make'],
            ['name' => 'model'],
            ['name' => 'registration'],
            ['name' => 'engine_size'],
            ['name' => 'price'],
            ['name' => 'tags'],
        ]);
    }
}
