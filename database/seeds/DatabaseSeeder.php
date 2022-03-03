<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        // $this->call(UsersTableSeeder::class);

         $permissions = [
        //    'blog-detail-setting',
        //    'service-book-setting',
        //    'service-detail-setting',
         ];
         
       foreach ($permissions as $permission){
           \Spatie\Permission\Models\Permission::where(['name' => $permission])->delete();
           \Spatie\Permission\Models\Permission::create(['name' => $permission,'guard_name' => 'admin']);
       }
    }
}
