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
        while ($i <= $number_of_products) {
            $data = [];
            //Above 65535 parameters I got an error on insert, so I am inserting in blocks of 65500(13100 *5)
            // while (count($data) < 13100 && $i <= $number_of_products) {
            // $data[] = [
            //     'title' => $faker->userName,
            //     'price' => $faker->randomFloat(2, 0, $max_product_price),
            //     'reviews_count' => $faker->randomNumber(),
            //     'avr_rating' => $faker->randomFloat(1, 0, 5),
            //     'first_listed' => $faker->dateTimeBetween($first_listed_price, $last_listed_price)
            // ];
            fputcsv($output, [
                'title' => $faker->userName,
                'price' => $faker->randomFloat(2, 0, $max_product_price),
                'reviews_count' => $faker->randomNumber(),
                'avr_rating' => $faker->randomFloat(1, 0, 5),
                'first_listed' => $faker->dateTimeBetween($first_listed_price, $last_listed_price)->format('Y-m-d H:i:s')
            ]);
            $i++;
            // };
            // Remazing_cc\Models\Product::insert($data);
        }
        fclose($output);
        // exec("PGPASSWORD=" . env('DB_USERNAME') . " psql -h postgres -d remazing_cc -U " . env('DB_USERNAME') . " -c \"\copy products(title,price,reviews_count,avr_rating,first_listed) FROM '/tmp/products.csv' delimiter ',' csv\"");
    }
}
