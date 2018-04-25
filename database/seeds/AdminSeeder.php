<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 系统管理员
        $funcList = Config::get('constants.MODULE_LIST');

        $idx = 0;
        $total = '';
        foreach ($funcList as $loop) {
            $total = $total . '1';
            $idx++;
        }

        DB::table('t_role')->insert([
            'id' => 1,
            'role_name' => '超级管理员',
            'role_right' => bindec($total),
        ]);
        DB::table('t_role')->insert([
            'id' => 2,
            'role_name' => '运维人员',
            'role_right' => bindec('1000111'),
        ]);
        DB::table('t_role')->insert([
            'id' => 3,
            'role_name' => '商品管理员',
            'role_right' => bindec('0001100'),
        ]);
        DB::table('t_role')->insert([
            'id' => 4,
            'role_name' => '文章管理员',
            'role_right' => bindec('0100000'),
        ]);
        //------------------------------------------------------------------------------------------//
        DB::table('t_admin')->insert([
            'id' => 1,
            'admin_name' => 'sunyu',
            'nick_name' => '孙宇',
            'admin_pwd' => md5('123456'),
            'role_id' => 1,
            'admin_email' => 'sunyu@edeng.com',
            'create_time' => date("Y-m-d H:i:s"),
            'status' => 1,
        ]);
        DB::table('t_admin')->insert([
            'id' => 2,
            'admin_name' => 'xiaolu',
            'nick_name' => '晓璐',
            'admin_pwd' => md5('123456'),
            'role_id' => 2,
            'admin_email' => 'xiaolu@edeng.com',
            'create_time' => date("Y-m-d H:i:s"),
            'status' => 1,
        ]);
        DB::table('t_admin')->insert([
            'id' => 3,
            'admin_name' => 'yaya',
            'nick_name' => '芽芽',
            'admin_pwd' => md5('123456'),
            'role_id' => 3,
            'admin_email' => 'yaya@edeng.com',
            'create_time' => date("Y-m-d H:i:s"),
            'status' => 1,
        ]);
        DB::table('t_admin')->insert([
            'id' => 4,
            'admin_name' => 'guoguo',
            'nick_name' => '果果',
            'admin_pwd' => md5('123456'),
            'role_id' => 4,
            'admin_email' => 'guoguo@edeng.com',
            'create_time' => date("Y-m-d H:i:s"),
            'status' => 1,
        ]);
    }
}
