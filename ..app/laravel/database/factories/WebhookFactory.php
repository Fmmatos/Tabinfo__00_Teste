<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\Webhooks>
 */
class WebhookFactory
{
    protected $model = \Vendor\Models\Webhooks::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'type' => $this->faker->lexify('????????????????????'),
            'status' => $this->faker->boolean(80),
            'orders' => $this->faker->optional(0.8)->numberBetween(1, 100),
            'gateway_id' => $this->faker->paragraph(5),
            'gateway_webhooks_id' => $this->faker->paragraph(5),
            'return__' => $this->faker->paragraph(5),
            'reponse' => $this->faker->paragraph(5),
            'request' => $this->faker->paragraph(5),
        ];
    }
}
