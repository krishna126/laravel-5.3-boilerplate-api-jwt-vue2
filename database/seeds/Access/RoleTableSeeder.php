<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{

    public function run()
    {

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.roles_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement("DELETE FROM " . config('access.roles_table'));
        } else //For PostgreSQL or anything else
        {
            DB::statement("TRUNCATE TABLE " . config('access.roles_table') . " CASCADE");
        }

        $roles = [
            [
                'name' => 'Admin',
                'all' => true,
                'sort' => 1,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'User',
                'all' => false,
                'sort' => 2,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ],
        ];
        try {
            if(config('access.role') && !empty(config('access.role'))){
                $roleModel = __NAMESPACE__ . '\\'. config('access.role');
                $role = new $roleModel;
                $role::insert($roles);
            }else{
              throw new Exception('In config role model could not be empty.');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }


        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
