<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{

    public function run()
    {

        if (env('DB_CONNECTION') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (env('DB_CONNECTION') == 'mysql') {
            DB::table(config('access.assigned_roles_table'))->truncate();
        } elseif (env('DB_CONNECTION') == 'sqlite') {
            DB::statement("DELETE FROM " . config('access.assigned_roles_table'));
        } else //For PostgreSQL or anything else
        {
            DB::statement("TRUNCATE TABLE " . config('access.assigned_roles_table') . " CASCADE");
        }

        try {
            if(config('auth.model') && !empty(config('auth.model'))){
                $userModel = __NAMESPACE__ . '\\'. config('auth.model');
                $user = new $userModel;
                $user::find(1)->roles()->sync([1]); //Attach admin role to admin user
                $user::find(2)->roles()->sync([2]); //Attach user role to general user
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
