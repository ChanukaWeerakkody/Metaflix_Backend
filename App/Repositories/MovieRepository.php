<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Language;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{

    /* ===== Category Repository ===== */

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
        $category->update([
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


/* ===== Language Repository ===== */

class LanguageRepository
{
    public function createLanguage(array $data)
    {
        $language_name = $data['language'] ?? null;

        if (!$language_name) {
            return [
                'success' => false,
                'message' => 'Language name is required.',
                'data' => null,
            ];
        }

        $language = Language::where('language', $language_name)
            ->where('is_active', 1)
            ->first();

        if ($language) {
            return [
                'success' => false,
                'message' => 'Language already exists.',
                'data' => null,
            ];
        } else {
            $language = Language::create([
                'language' => $language_name,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Language created successfully.',
                'data' => $language,
            ];
        }
    }

    public function getAllLanguages()
    {
        $language = Language::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->get();

        return [
            'success' => true,
            'message' => 'Languages fetched successfully.',
            'data' => $language,
        ];
    }

    public function getSingleLanguage(int $language_id)
    {
        if ($language_id <= 0) {
            return [
                'success' => false,
                'message' => 'Language id is required.',
                'data' => null,
            ];
        }

        $language = Language::where('id', $language_id)
            ->where('is_active', 1)
            ->first();

        if (!$language) {
            return [
                'success' => false,
                'message' => 'Language not found.',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Language fetched successfully.',
            'data' => $language,
        ];
    }

    public function updateLanguage(array $data)
    {
        $language_id = (int) ($data['language_id'] ?? 0);
        $language_name = $data['language'] ?? null;
        $updated_by = (int) ($data['updated_by'] ?? 0);

        if ($language_id <= 0) {
            return [
                'success' => false,
                'message' => 'Language id is required.',
                'data' => null,
            ];
        }

        if (!$language_name) {
            return [
                'success' => false,
                'message' => 'Language name is required.',
                'data' => null,
            ];
        }

        if ($updated_by <= 0) {
            return [
                'success' => false,
                'message' => 'Updated by is required.',
                'data' => null,
            ];
        }

        $language = Language::where('id', $language_id)
            ->where('is_active', 1)
            ->first();

        if (!$language) {
            return [
                'success' => false,
                'message' => 'Language not found.',
                'data' => null,
            ];
        }

        $existing_language = Language::where('language', $language_name)
            ->where('is_active', 1)
            ->where('id', '!=', $language_id)
            ->first();

        if ($existing_language) {
            return [
                'success' => false,
                'message' => 'Language already exists.',
                'data' => null,
            ];
        }

        $language->language = $language_name;
        $language->updated_by = $updated_by;
        $language->updated_at = now();
        $language->save();

        return [
            'success' => true,
            'message' => 'Language updated successfully.',
            'data' => $language,
        ];
    }

    public function deleteLanguage(array $data)
    {
        $language_id = (int) ($data['language_id'] ?? 0);
        $deleted_by = (int) ($data['deleted_by'] ?? 0);

        if ($language_id <= 0 || $deleted_by <= 0) {
            return [
                'success' => false,
                'message' => 'Language id and deleted by are required.',
                'data' => null,
            ];
        }

        $language = Language::where('id', $language_id)
            ->where('is_active', 1)
            ->first();

        if (!$language) {
            return [
                'success' => false,
                'message' => 'Language not found.',
                'data' => null,
            ];
        }

        $language->update([
            'is_active' => 0,
            'deleted_by' => $deleted_by,
            'deleted_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Language deleted successfully.',
            'data' => null,
        ];
    }

    public function getSingaleLanguage(int $language_id)
    {
        if ($language_id <= 0) {
            return [
                'success' => false,
                'message' => 'Language id is required.',
                'data' => null,
            ];
        }

        $language = Language::where('id', $language_id)
            ->where('is_active', 1)
            ->first();

        if (!$language) {
            return [
                'success' => false,
                'message' => 'Language not found.',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Language fetched successfully.',
            'data' => $language,
        ];
    }
}
