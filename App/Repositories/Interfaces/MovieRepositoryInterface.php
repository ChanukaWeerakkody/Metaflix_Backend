<?php

namespace App\Repositories\Interfaces;

interface MovieRepositoryInterface
{

    public function createCategory(array $data);
    public function updateCategory(array $data);

    public function deleteCategory(array $data);
    public function getAllCategories();
    public function getSingleCategory(int $category_id);
}
