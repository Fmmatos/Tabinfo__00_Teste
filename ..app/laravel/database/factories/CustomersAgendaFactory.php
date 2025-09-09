<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Root\Models\CustomersAgenda>
 */
class CustomersAgendaFactory
{
    protected $model = \Root\Models\CustomersAgenda::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'active' => $this->faker->boolean(80),
            'name' => $this->faker->name(),
            'image' => $this->faker->optional(0.8)->paragraph(5),
            'type' => $this->faker->optional(0.8)->lexify('????????????????????'),
            'order' => $this->faker->numberBetween(1, 999),
            'customers' => $this->faker->optional(0.8)->randomElement(DB::table('y_menu_admin')->pluck('id')->toArray()),
        ];
    }
}
