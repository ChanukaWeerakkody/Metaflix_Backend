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
}
