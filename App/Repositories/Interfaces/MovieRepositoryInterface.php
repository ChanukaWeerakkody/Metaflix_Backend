<?php

namespace App\Repositories\Interfaces;

interface MovieRepositoryInterface
{

    public function createCategory(array $data);
    public function updateCategory(array $data);
    public function deleteCategory(array $data);
    public function getAllCategories();
    public function getSingleCategory(int $category_id);

    public function createLanguage(array $data);
    public function updateLanguage(array $data);
    public function deleteLanguage(array $data);
    public function getAllLanguages();
    public function getSingleLanguage(int $language_id);

    public function createMovieRoll(array $data);
    public function updateMovieRoll(array $data);
    public function deleteMovieRoll(array $data);
    public function getMovieRolls();
    public function getSingleMovieRoll(int $language_id);

    public function createMovie(array $data);
    public function updateMovie(array $data);
    public function deleteMovie(array $data);
    public function getAllMovies();
    public function getSingleMovie(int $movie_id);
}
