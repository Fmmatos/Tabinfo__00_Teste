<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\YMenuAdminColumns>
 */
class YMenuAdminColumnFactory
{
    protected $model = \Vendor\Models\YMenuAdminColumns::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'y_menu_admin' => DB::table('y_menu_admin')->inRandomOrder()->value('id') ?: 1,
            'users' => DB::table('users')->inRandomOrder()->value('id') ?: 1,
            'value' => $this->faker->paragraph(5),
        ];
    }
}
