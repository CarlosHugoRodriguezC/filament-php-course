<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryStateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // read json
        $countries = json_decode(
            file_get_contents(
                database_path('data/countries.json')
            ),
            true
        );

        // insert countries
        foreach ($countries as $country) {
            $country['is_active'] = true;
            $country['timezones'] = json_encode($country['timezones']);
            $country['translations'] = json_encode($country['translations']);
            $country['created_at'] = now();
            $country['updated_at'] = now();
            \DB::table('countries')->insert($country);
        }

        // read json
        $states = json_decode(
            file_get_contents(
                database_path('data/states.json')
            ),
            true
        );

        // insert states

        foreach ($states as $state) {
            $state['is_active'] = true;
            $state['created_at'] = now();
            $state['updated_at'] = now();
            \DB::table('states')->insert($state);
        }

        // read json
        $cities = json_decode(
            file_get_contents(
                database_path('data/cities.json')
            ),
            true
        );

        // insert cities
        foreach ($cities as $city) {
            $city['is_active'] = true;
            $city['created_at'] = now();
            $city['updated_at'] = now();
            \DB::table('cities')->insert($city);
        }
    }
}
