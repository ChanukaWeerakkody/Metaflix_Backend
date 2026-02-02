<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{


    public function createCategory(array $data)
    {
        $category_name = $data['category_name'] ?? null;
        $created_by = (int) $data['created_by'] ?? 0;



        if (!$category_name || $created_by <= 0) {
            return [
                'success' => false,
                'message' => 'Category name and created by are required.',
                'data' => null,
            ];
        }

        $category = Category::where('category_name', $category_name)
            ->where('is_active', 1)
            ->first();


        if ($category) {
            return [
                'success' => false,
                'message' => 'Category name already exists.',
                'data' => null,
            ];
        } else {
            $category = Category::create([
                'category_name' => $category_name,
                'is_active' => 1,
                'created_by' => $created_by,
                'updated_by' => $created_by,
                'created_at' => now(),
                'updated_at' => now(),

            ]);

            return [
                'success' => true,
                'message' => 'Category created successfully.',
                'data' => [
                    'category_id' => $category->id,
                ],
            ];
        }
    }
}
