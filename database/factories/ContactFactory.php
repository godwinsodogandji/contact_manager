<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        $countries = [
            ['name' => 'France', 'flag' => 'https://flagcdn.com/fr.svg'],
            ['name' => 'United States', 'flag' => 'https://flagcdn.com/us.svg'],
            ['name' => 'Nigeria', 'flag' => 'https://flagcdn.com/ng.svg'],
        ];
        $country = $this->faker->randomElement($countries);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company' => $this->faker->company(),
            'country' => $country['name'],
            'country_flag' => $country['flag'],
        ];
    }
}