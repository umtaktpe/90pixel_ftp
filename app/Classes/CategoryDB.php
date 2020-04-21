<?php

namespace App\Classes;

use SimpleXLSX;
use App\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class CategoryDB
{
    public $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Parse the excel file downloaded from ftp
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToDB()
    {
        $categories = $this->excelParser();
        // Remove the excel file after job finished.
        File::delete(public_path('/' . $this->file));
        try {
            foreach ($categories as $category) {
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

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function excelParser()
    {
        $total = [];
        $result = [];

        try {
            // Read the excel file and parse.
            if ($xlsx = SimpleXLSX::parse(public_path('/' . $this->file))) {
                // Produce array keys from the array values of 1st array element
                $header_values = $rows = [];
                foreach ($xlsx->rows() as $k => $r) {
                    if ($k === 0) {
                        $header_values = $r;
                        continue;
                    }
                    $rows[] = array_combine($header_values, $r);
                }
            }

            foreach ($rows as $row) {
                for ($i = 0; $i < count($row); $i++) {
                    $number = $i + 1;
                    $total[$i] = Arr::pluck($rows, "Kategori $number");
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during excel parser'
            ]);
        }

        $rootCategory = $total[0];

        // Root category created.
        foreach ($rootCategory as $rootValue) {
            if (!$this->checkIfValueExist($result, "category_name", $rootValue)) {
                $parentArray = ["category_name" => $rootValue];
                $currentLayerNo = 1;
                $this->addChildToParent($parentArray, $total, $currentLayerNo);
                array_push($result, $parentArray);
            }
        }

        // Parent array make unique and return.
        return array_map("unserialize", array_unique(array_map("serialize", $result)));
    }

    /**
     * Categories were nested.
     *
     * @param $parentArray
     * @param $total
     * @param $currentLayerNo
     */
    public function addChildToParent(&$parentArray, $total, &$currentLayerNo)
    {
        if ($currentLayerNo != count($total)) {
            $childList = $total[$currentLayerNo];
            foreach ($childList as $childKey => $childValue) {
                $parentName = $total[$currentLayerNo - 1][$childKey];

                if (!$this->checkIfValueExist($parentArray, "category_name", $childValue)) {
                    if ($parentArray["category_name"] == $parentName && $childValue != "") {
                        if (!isset($parentArray["children"])) {
                            $parentArray["children"] = [];
                        }
                        $childArray = ["category_name" => $childValue];
                        $newCounter = $currentLayerNo + 1;
                        $this->addChildToParent($childArray, $total, $newCounter);
                        array_push($parentArray["children"], $childArray);
                        // Child arrays make unique.
                        $parentArray["children"] = array_map("unserialize", array_unique(array_map("serialize", $parentArray["children"])));
                    }
                }
            }
        }
    }

    /**
     * @param $searchArray
     * @param $searchKey
     * @param $searchValue
     * @return bool
     */
    public function checkIfValueExist($searchArray, $searchKey, $searchValue)
    {
        return (isset($searchArray[$searchKey]) && $searchArray[$searchKey] == $searchValue) ? true :  false;
    }

}