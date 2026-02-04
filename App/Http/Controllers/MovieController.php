<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    protected $movieRepository;
    protected $languageRepository;

    public function __construct(
        MovieRepositoryInterface $movieRepository,
        \App\Repositories\LanguageRepository $languageRepository
    ) {
        $this->movieRepository = $movieRepository;
        $this->languageRepository = $languageRepository;
    }

    /* ===== Caregory APIs ===== */

    public function createCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string',
                'created_by' => 'required|int',
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->movieRepository->createCategory($data);

                $output['success'] = $out_data['success'];
                $output['message'] = $out_data['message'];
                $output['data'] = $out_data['data'];
            }
        } catch (\Exception $e) {
            $url = $request->url();
            $error_message = $e->getMessage();
            $this->logError($url, $error_message);
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }

        return response()->json(['success' => $output['success'], 'message' => $output['message'], 'output' => $output['data']], 200);
    }

    public function updateCategory(Request $request, $category_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string',
                'updated_by' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Data didn't pass correctly.",
                    'output' => $validator->errors()->first(),
                ], 422);
            }

            $data = $request->all();
            $data['category_id'] = (int) $category_id;

            $out_data = $this->movieRepository->updateCategory($data);

            return response()->json([
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'output' => $out_data['data'],
            ], 200);
        } catch (\Exception $e) {
            $this->logError($request->url(), $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'output' => null,
            ], 500);
        }
    }

    public function deleteCategory(Request $request, $category_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'deleted_by' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Data didn't pass correctly.",
                    'output' => $validator->errors()->first(),
                ], 422);
            }

            $data = $request->all();
            $data['category_id'] = (int) $category_id;

            $out_data = $this->movieRepository->deleteCategory($data);
            return response()->json([
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'output' => $out_data['data'],
            ], 200);
        } catch (\Exception $e) {
            $this->logError($request->url(), $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'output' => null,
            ], 500);
        }
    }

    public function getcategoryById($category_id)
    {
        try {
            $out_data = $this->movieRepository->getSingleCategory($category_id);
            $output['success'] = $out_data['success'];
            $output['message'] = $out_data['message'];
            $output['data'] = $out_data['data'];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }
        return response()->json([
            'success' => $output['success'],
            'message' => $output['message'],
            'output' => $output['data']
        ], 200);
    }

    public function getAllCategories()
    {
        try {
            $out_data = $this->movieRepository->getAllCategories();

            $out_put = [
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'data' => $out_data['data'],
            ];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }
        return response()->json([
            'success' => $out_data['success'],
            'message' => $out_data['message'],
            'output' => $out_put['data']
        ], 200);
    }

    /* ===== Language APIs ===== */
    public function createLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language' => 'required|string',
            'created_by' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'output' => $validator->errors()->first()
            ], 422);
        }

        $out_data = $this->languageRepository->createLanguage($request->all());

        return response()->json([
            'success' => $out_data['success'],
            'message' => $out_data['message'],
            'output' => $out_data['data'],
        ], 200);
    }

    public function getAllLanguages()
    {
        try {
            $out_data = $this->languageRepository->getAllLanguages();
            $out_data = [
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'data' => $out_data['data'],
            ];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }
        return response()->json([
            'success' => $out_data['success'],
            'message' => $out_data['message'],
            'output' => $out_data['data']
        ], 200);
    }

    public function getLanguageById($language_id)
    {
        try {
            $out_data = $this->languageRepository->getSingleLanguage($language_id);
            $output['success'] = $out_data['success'];
            $output['message'] = $out_data['message'];
            $output['data'] = $out_data['data'];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }
        return response()->json([
            'success' => $output['success'],
            'message' => $output['message'],
            'output' => $output['data']
        ], 200);
    }

    public function updateLanguage(Request $request, $language_id)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'language' => 'required|string',
                'updated_by' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'output' => $validator->errors()->first()
                ], 422);
            }
            $data = $request->all();
            $data['language_id'] = (int) $language_id;

            $out_data = $this->languageRepository->updateLanguage($data);

            return response()->json([
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'output' => $out_data['data'],
            ], 200);
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'output' => null
            ], 500);
        }
    }

    public function deleteLanguage(Request $request, $language_id)
    {
        try {

            $data = [
                'language_id' => (int) $language_id,
                'deleted_by'  => 1,
            ];

            $out_data = $this->languageRepository->deleteLanguage($data);

            return response()->json([
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'output'  => $out_data['data'],
            ], 200);
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'output'  => null
            ], 500);
        }
    }
}
