<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieCreateRepository
{
    public function create(array $data)
    {
        $validated = $this->validateMovieData($data);

        if (!$validated['success']) {
            return $validated;
            
        }

        if ($this->movieExists($data['title'])) {
            return [
                'success' => false,
                'message' => 'Movie already exists.',
                'data' => null,
            ];
        }

        $movie = Movie::create([
            'title' => $data['title'],
            'sub_title' => $data['sub_title'],
            'rate' => $data['rate'],
            'quality' => $data['quality'],
            'duration' => $data['duration'],
            'country' => $data['country'],
            'language_id' => (int) $data['language_id'],
            'category_id' => $data['category_id'],
            'year' => (int) $data['year'],
            'subtitle_by' => $data['subtitle_by'],
            'description' => $data['description'],
            'cover_image' => $data['cover_image'],
            'main_image' => $data['main_image'],
            'created_by' => $data['created_by'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Movie created successfully.',
            'data' => $movie,
        ];
    }

    private function validateMovieData(array $data): array
    {
        $required = [
            'title',
            'sub_title',
            'rate',
            'quality',
            'duration',
            'country',
            'language_id',
            'category_id',
            'year',
            'subtitle_by',
            'description',
            'cover_image',
            'main_image'
        ];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                return [
                    'success' => false,
                    'message' => "Field {$field} is required.",
                    'data' => null,
                ];
            }
        }

        return ['success' => true];
    }

    private function movieExists(string $title): bool
    {
        return Movie::where('title', $title)->exists();
    }
}
