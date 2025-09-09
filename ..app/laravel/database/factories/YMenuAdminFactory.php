<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\YMenuAdmin>
 */
class YMenuAdminFactory
{
    protected $model = \Vendor\Models\YMenuAdmin::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'active' => $this->faker->boolean(80),
            'table__' => $this->faker->sentence(5),
            'categories' => DB::table('y_menu_admin_categories')->inRandomOrder()->value('id') ?: 1,
            'name' => $this->faker->name(),
            'icon' => $this->faker->optional(0.8)->sentence(5),
            'type' => $this->faker->numberBetween(1, 100),
            'type_items' => $this->faker->optional(0.8)->lexify('????????????????????'),
            'filter' => $this->faker->optional(0.8)->paragraph(5),
            'orderby' => $this->faker->optional(0.8)->paragraph(5),
            'order' => $this->faker->numberBetween(1, 999),
        ];
    }
}
