<?php

use App\Category;
use Illuminate\Database\Seeder;

class FakeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 10) as $range) {
            $category = factory(Category::class)->create([
                'name' => 'Category ' . rand(10000, 99999)
            ]);


        }
    }
}
