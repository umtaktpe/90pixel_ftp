<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factories = factory(Category::class, 5)->make();

        $categories = [];
        foreach ($factories as $factory) {
            $categories[] = $factory->getAttributes();
        }

        dd($categories);

        foreach ($categories as $category) {
            Category::create($category->getAttributes());
        }
    }
}
