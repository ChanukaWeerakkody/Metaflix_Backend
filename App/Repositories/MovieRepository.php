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

    public function updateCategory(array $data)
    {
        $category_id = (int) ($data['category_id'] ?? 0);
        $category_name = $data['category_name'] ?? null;
        $updated_by = (int) ($data['updated_by'] ?? 0);

        if ($category_id <= 0 || !$category_name || $updated_by <= 0) {
            return [
                'success' => false,
                'message' => 'Category id, category name and updated by are required.',
                'data' => null,
            ];
        }

        $catgegory = Category::where('id', $category_id)
            ->where('is_active', 1)
            ->first();

        if (!$catgegory) {
            return [
                'success' => false,
                'message' => 'Category not found.',
                'data' => null,
            ];
        }
        $existing_category = Category::where('category_name', $category_name)
            ->where('is_active', 1)
            ->where('id', '!=', $category_id)
            ->first();

        if ($existing_category) {
            return [
                'success' => false,
                'message' => 'Category name already exists.',
                'data' => null,
            ];
        }
        $catgegory->update([
            'category_name' => $category_name,
            'updated_by' => $updated_by,
            'updated_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => null,
        ];
    }

    public function deleteCategory(array $data)
    {
        $category_id = (int) ($data['category_id'] ?? 0);
        $deleted_by = (int) ($data['deleted_by'] ?? 0);

        if ($category_id <= 0 || $deleted_by <= 0) {
            return [
                'success' => false,
                'message' => 'Category id and deleted by are required.',
                'data' => null,
            ];
        }

        $category = Category::where('id', $category_id)
            ->where('is_active', 1)
            ->first();

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Category not found.',
                'data' => null,
            ];
        }

        $category->update([
            'is_active' => 0,
            'deleted_by' => $deleted_by,
            'deleted_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Category deleted successfully.',
            'data' => null,
        ];
    }

    public function getAllCategories()
    {
        $categories = Category::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();

        return [
            'success' => true,
            'message' => 'Categories fetched successfully.',
            'data' => $categories,
        ];
    }

    public function getSingleCategory(int $category_id)
    {
        if ($category_id <= 0) {
            return [
                'success' => false,
                'message' => 'Category id is required.',
                'data' => null,
            ];
        }

        $category = Category::where('id', $category_id)
            ->where('is_active', 1)
            ->first();

        if (!$category) {
            return [
                'success' => false,
                'message' => 'Category not found.',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Category fetched successfully.',
            'data' => $category,
        ];
    }
}
