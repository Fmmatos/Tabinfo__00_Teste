<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\Texts>
 */
class TextFactory
{
    protected $model = \Vendor\Models\Texts::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'active' => $this->faker->boolean(80),
            'name_main' => $this->faker->sentence(5),
            'type' => $this->faker->lexify('????????????????????'),
            'name' => $this->faker->name(),
            'image' => $this->faker->optional(0.8)->paragraph(5),
            'place' => $this->faker->numberBetween(1, 100),
            'whatsapp' => $this->faker->optional(0.8)->paragraph(5),
            'sms' => $this->faker->optional(0.8)->paragraph(5),
            'order' => $this->faker->numberBetween(1, 999),
        ];
    }
}
