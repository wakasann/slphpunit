<?php

use App\Fruit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FruitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        \DB::table('fruits')->delete();
        $fruits = [
            ['name' => 'orange', 'color' => 'orange', 'weight' => 100, 'delicious' => 1],
            ['name' => 'banana', 'color' => 'yellow', 'weight' => 116, 'delicious' => 1],
            ['name' => 'apple', 'color' => 'red', 'weight' => 150, 'delicious' => 1]
        ];

        // Loop through fruits above and create the record in DB
        // 循环上面的水果和将记录保存到DB中
        foreach ($fruits as $fruit) {
            Fruit::create($fruit);
        }
        Model::reguard();
    }
}
