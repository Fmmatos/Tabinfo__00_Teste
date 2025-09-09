<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Vendor\Models\Customers>
 */
class CustomerFactory
{
    protected $model = \Vendor\Models\Customers::class;
    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function definition()
    {
        return [
            'active' => $this->faker->boolean(80),
            'approved' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->name(),
            'image' => $this->faker->optional(0.8)->paragraph(5),
            'type' => $this->faker->lexify('????????????????????'),
            'customers' => $this->faker->optional(0.8)->randomElement(DB::table('customers')->pluck('id')->toArray()),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->optional(0.8)->numerify('###.###.###-##'),
            'cnpj' => $this->faker->optional(0.8)->numerify('##.###.###/####-##'),
            'ie' => $this->faker->optional(0.8)->sentence(5),
            'phone' => $this->faker->optional(0.8)->numerify('(##) #####-####'),
            'birth' => $this->faker->optional(0.8)->dateTimeBetween('-60 years', '-18 years') ? $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d') : null,
            'sexo' => $this->faker->optional(0.8)->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'url' => $this->faker->optional(0.8)->regexify('https://[a-z]{5,10}\.com'),
            'password' => Hash::make('password'),
            'image_media' => $this->faker->optional(0.8)->paragraph(5),
            'certificates' => $this->faker->optional(0.8)->paragraph(5),
            'categories' => $this->faker->optional(0.8)->randomElement(DB::table('customers_categories')->pluck('id')->toArray()),
            'height' => $this->faker->randomFloat(2, 0, 1000),
            'weight' => $this->faker->randomFloat(2, 0, 1000),
            'links_videos' => $this->faker->optional(0.8)->paragraph(5),
            'zipcode' => $this->faker->optional(0.8)->numerify('#####-###'),
            'address' => $this->faker->optional(0.8)->streetName(),
            'number' => $this->faker->optional(0.8)->buildingNumber(),
            'complement' => $this->faker->optional(0.3)->randomElement([null, $this->faker->secondaryAddress()]),
            'neighborhood' => $this->faker->optional(0.8)->citySuffix(),
            'uf' => $this->faker->optional(0.8)->stateAbbr(),
            'city' => $this->faker->optional(0.8)->city(),
            'order' => $this->faker->numberBetween(1, 999),
            'remember_token' => $this->faker->optional(0.8)->sentence(5),
            'last_acess' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s') : null,
            'verified_at' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s') : null,
        ];
    }
}
