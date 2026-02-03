<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

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

    public function updateCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|int',
                'category_name' => 'required|string',
                'updated_by' => 'required|int',
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->movieRepository->updateCategory($data);

                $output['success'] = $out_data['success'];
                $output['message'] = $out_data['message'];
                $output['data'] = $out_data['data'];
            }
        } catch (\Exception $e) {
            $this->logError($request->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }
        return response()->json(['success' => $output['success'], 'message' => $output['message'], 'output' => $output['data']], 200);
    }

    public function deleteCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|int',
                'deleted_by' => 'required|int',
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->movieRepository->deleteCategory($data);

                $output['success'] = $out_data['success'];
                $output['message'] = $out_data['message'];
                $output['data'] = $out_data['data'];
            }
        } catch (\Exception $e) {
            $this->logError($request->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        }
        return response()->json(['success' => $output['success'], 'message' => $output['message'], 'output' => $output['data']], 200);
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
}
