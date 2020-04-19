<?php

namespace App\Classes;

use App\Category;

class CategoryDB
{
    public $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function addToDB()
    {
        try {
            foreach ($this->categories as $category) {
                Category::create($category);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data added to database successfully.'
        ]);
    }

}