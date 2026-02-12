<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Language;
use App\Models\Movie;
use App\Models\MovieRole;
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

    public function createMovieRoll(array $data)
    {

        $roll_name =  $data['role'] ?? null;
        $created_by = (int) ($data['created_by'] ?? 0);

        if (!$roll_name || $created_by <= 0) {
            return [
                'success' => false,
                'message' => 'Roll name and created by are required.',
                'data' => null,
            ];
        }
        $roll = MovieRole::where('role', $roll_name)
            ->where('is_active', 1)
            ->first();

        if ($roll) {
            return [
                'success' => false,
                'message' => 'Roll already exists.',
                'data' => null,
            ];
        } else {
            $roll = MovieRole::create([
                'role' => $roll_name,
                'is_active' => 1,
                'created_by' => $created_by,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return [
                'success' => true,
                'message' => 'Roll created successfully.',
                'data' => $roll,
            ];
        }
    }

    public function getMovieRolls()
    {
        $roll = MovieRole::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return [
            'success' => true,
            'message' => 'Rolls fetched successfully.',
            'data' => $roll,
        ];
    }

    public function getSingleMovieRoll(int $roll_id)
    {
        if ($roll_id <= 0) {
            return [
                'success' => false,
                'message' => 'Roll id is required.',
                'data' => null,
            ];
        }

        $roll = MovieRole::where('id', $roll_id)
            ->where('is_active', 1)
            ->first();

        if (!$roll) {
            return [
                'success' => false,
                'message' => 'Roll not found.',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Roll fetched successfully.',
            'data' => $roll,
        ];
    }

    public function updateMovieRoll(array $data)
    {
        $roll_id = (int) ($data['roll_id'] ?? 0);
        $roll_name = $data['role'] ?? null;
        $updated_by = (int) ($data['updated_by'] ?? 0);

        if ($roll_id <= 0 || !$roll_name || $updated_by <= 0) {
            return [
                'success' => false,
                'message' => 'Roll id, roll name and updated by are required.',
                'data' => null,
            ];
        }

        $roll = MovieRole::where('id', $roll_id)
            ->where('is_active', 1)
            ->first();

        if (!$roll) {
            return [
                'success' => false,
                'message' => 'Roll not found.',
                'data' => null,
            ];
        }

        $existing_roll = MovieRole::where('role', $roll_name)
            ->where('is_active', 1)
            ->where('id', '!=', $roll_id)
            ->first();

        if ($existing_roll) {
            return [
                'success' => false,
                'message' => 'Roll already exists.',
                'data' => null,
            ];
        } else {
            $roll->update([
                'role' => $roll_name,
                'updated_by' => $updated_by,
                'updated_at' => now(),
            ]);
            return [
                'success' => true,
                'message' => 'Roll updated successfully.',
                'data' => $roll,
            ];
        }
    }

    public function deleteMovieRoll(array $data)
    {
        $roll_id = (int) ($data['roll_id'] ?? 0);
        $deleted_by = (int) ($data['deleted_by'] ?? 0);

        if ($roll_id <= 0 || $deleted_by <= 0) {
            return [
                'success' => false,
                'message' => 'Roll id and deleted by are required.',
                'data' => null,
            ];
        }

        $roll = MovieRole::where('id', $roll_id)
            ->where('is_active', 1)
            ->first();


        if (!$roll) {
            return [
                'success' => false,
                'message' => 'Roll not found.',
                'data' => null,
            ];
        } else {
            $roll->update([
                'is_active' => 0,
                'deleted_by' => $deleted_by,
                'deleted_at' => now(),
            ]);
            return [
                'success' => true,
                'message' => 'Roll deleted successfully.',
                'data' => $roll,
            ];
        }
    }

    public function createMovie(array $data)
    {
        $title        = $data['title'] ?? null;
        $sub_title    = $data['sub_title'] ?? null;
        $rate         = $data['rate'] ?? null;
        $quality      = $data['quality'] ?? null;
        $duration     = $data['duration'] ?? null;
        $country      = $data['country'] ?? null;
        $language_id  = $data['language_id'] ?? null;
        $category_id  = $data['category_id'] ?? null;
        $year         = $data['year'] ?? null;
        $subtitle_by  = $data['subtitle_by'] ?? null;
        $description  = $data['description'] ?? null;
        $cover_image  = $data['cover_image'] ?? null;
        $main_image   = $data['main_image'] ?? null;


        if (
            !$title || !$sub_title || !$rate || !$quality || !$duration ||
            !$country || !$language_id || !$category_id || !$year ||
            !$subtitle_by || !$description || !$cover_image || !$main_image
        ) {
            return [
                'success' => false,
                'message' => 'All fields are required.',
                'data' => null,
            ];
        }


        $movie = Movie::where('title', $title)->first();

        if ($movie) {
            return [
                'success' => false,
                'message' => 'Movie already exists.',
                'data' => null,
            ];
        }


        $movie = Movie::create([
            'title'        => $title,
            'sub_title'    => $sub_title,
            'rate'         => $rate,
            'quality'      => $quality,
            'duration'     => $duration,
            'country'      => $country,
            'language_id'  => (int) $language_id,
            'category_id'  => $category_id,
            'year'         => (int) $year,
            'subtitle_by'  => $subtitle_by,
            'description'  => $description,
            'cover_image'  => $cover_image,
            'main_image'   => $main_image,
        ]);

        return [
            'success' => true,
            'message' => 'Movie created successfully.',
            'data' => $movie,
        ];
    }

    public function getAllMovies()
    {
        $movies = Movie::where('is_active', 1)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return [
            'success' => true,
            'message' => 'Movies fetched successfully.',
            'data' => $movies,
        ];
    }

    public function getSingleMovie(int $movie_id)
    {
        if ($movie_id <= 0) {
            return [
                'success' => false,
                'message' => 'Movie id is required.',
                'data' => null,
            ];
        }

        $movie = Movie::where('id', $movie_id)
            ->where('is_active', 1)
            ->first();

        if (!$movie) {
            return [
                'success' => false,
                'message' => 'Movie not found.',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Movie fetched successfully.',
            'data' => $movie,
        ];
    }


    public function updateMovie(array $data)
    {
        $movie_id = (int) ($data['movie_id'] ?? 0);
        $title        = $data['title'] ?? null;
        $sub_title    = $data['sub_title'] ?? null;
        $rate         = $data['rate'] ?? null;
        $quality      = $data['quality'] ?? null;
        $duration     = $data['duration'] ?? null;
        $country      = $data['country'] ?? null;
        $language_id  = $data['language_id'] ?? null;
        $category_id  = $data['category_id'] ?? null;
        $year         = $data['year'] ?? null;
        $subtitle_by  = $data['subtitle_by'] ?? null;
        $description  = $data['description'] ?? null;
        $cover_image  = $data['cover_image'] ?? null;
        $main_image   = $data['main_image'] ?? null;

        if (
            !$movie_id || !$title || !$sub_title || !$rate || !$quality || !$duration ||
            !$country || !$language_id || !$category_id || !$year ||
            !$subtitle_by || !$description || !$cover_image || !$main_image
        ) {
            return [
                'success' => false,
                'message' => 'All fields are required.',
                'data' => null,
            ];
        }

        $movie = Movie::where('id', $movie_id)->first();

        if (!$movie) {
            return [
                'success' => false,
                'message' => 'Movie not found.',
                'data' => null,
            ];
        }

        $exist_movie = Movie::where('title', $title)->where('id', '!=', $movie_id)->first();

        if ($exist_movie) {
            return [
                'success' => false,
                'message' => 'Movie already exists.',
                'data' => null,
            ];
        } else {
            $movie->update([
                'title'        => $title,
                'sub_title'    => $sub_title,
                'rate'         => $rate,
                'quality'      => $quality,
                'duration'     => $duration,
                'country'      => $country,
                'language_id'  => $language_id,
                'category_id'  => $category_id,
                'year'         => $year,
                'subtitle_by'  => $subtitle_by,
                'description'  => $description,
                'cover_image'  => $cover_image,
                'main_image'   => $main_image,
            ]);
            return [
                'success' => true,
                'message' => 'Movie updated successfully.',
                'data' => $movie,
            ];
        }
    }

    public function deleteMovie(array $data)
    {
        $movie_id = (int) ($data['movie_id'] ?? 0);

        if ($movie_id <= 0) {
            return [
                'success' => false,
                'message' => 'Movie id is required.',
                'data' => null,
            ];
        }

        $movie = Movie::where('id', $movie_id)->first();

        if (!$movie) {
            return [
                'success' => false,
                'message' => 'Movie not found.',
                'data' => null,
            ];
        }
        $movie->update(['is_active' => 0]);
        return [
            'success' => true,
            'message' => 'Movie deleted successfully.',
            'data' => $movie,
        ];
    }
}
