<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\XSettings>
 */
class XSettingFactory
{
    protected $model = \Vendor\Models\XSettings::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'fields' => $this->faker->lexify('????????????????????'),
            'value' => $this->faker->paragraph(5),
        ];
    }
}
