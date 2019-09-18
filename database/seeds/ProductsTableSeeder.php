<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $number_of_products = env('THOUSANDS_OF_PRODUCTS') * 1000;
        $max_product_price = env('MAXIMUM_PRODUCT_PRICE');
        $first_listed_price = env('FIRST_LISTED_DATE');
        $last_listed_price = env('LAST_LISTED_DATE');

        // Methods of seeding tried
        // Using Factory took 21 seconds each 100000 records
        // Using blocks of 65k inserts took 13 seconds each 100000 records
        // Using psql 5 seconds each 100000

        DB::disableQueryLog();

        $faker = Faker\Factory::create();
        $output = fopen("/tmp/products.csv", "w");
        $i = 0;
        while ($i < $number_of_products) {
            fputcsv($output, [
                'title' => $faker->userName,
                'price' => $faker->randomFloat(2, 0, $max_product_price),
                'reviews' => $faker->randomNumber(7),
                'avr_rating' => $faker->randomFloat(1, 0, 5),
                'first_listed' => $faker->dateTimeBetween($first_listed_price, $last_listed_price)->format(DATE_ISO8601)
            ]);
            $i++;
        }
        fclose($output);
        exec("PGPASSWORD=" . env('DB_PASSWORD') . " psql -h postgres -d remazing_cc -U " . env('DB_USERNAME') . " -c \"\copy products(title,price,reviews,avr_rating,first_listed) FROM '/tmp/products.csv' delimiter ',' csv\"");
    }
}
