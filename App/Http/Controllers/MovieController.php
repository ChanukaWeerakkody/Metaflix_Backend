<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Exception;

class MovieController extends Controller
{
    protected $movieRepository;

    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;
    protected $preset;
    protected $url;


    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->cloudName = env('CLOUDINARY_CLOUD_NAME');
        $this->apiKey = env('CLOUDINARY_API_KEY');
        $this->apiSecret = env('CLOUDINARY_API_SECRET');
        $this->preset = env('CLOUDINARY_PRESET');
        $this->url = env('CLOUDINARY_URL');
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

        $out_data = $this->movieRepository->createLanguage($request->all());

        return response()->json([
            'success' => $out_data['success'],
            'message' => $out_data['message'],
            'output' => $out_data['data'],
        ], 200);
    }

    public function getAllLanguages()
    {
        try {
            $out_data = $this->movieRepository->getAllLanguages();
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
            $out_data = $this->movieRepository->getSingleLanguage($language_id);
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

            $out_data = $this->movieRepository->updateLanguage($data);

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

    public function deleteLanguage($language_id)
    {
        try {

            $data = [
                'language_id' => (int) $language_id,
                'deleted_by' => 1,
            ];

            $out_data = $this->movieRepository->deleteLanguage($data);

            return response()->json([
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'output' => $out_data['data'],
            ], 200);
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'output' => null
            ], 500);
        }
    }

    public function createMovieRoll(Request $request)
    {
        try {
            $input = $request->all();

            // Validation for bulk or single
            $validator = Validator::make(
                $input,
                isset($input[0])
                    ? ['*.role' => 'required|string', '*.created_by' => 'required|integer']
                    : ['role' => 'required|string', 'created_by' => 'required|integer']
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'output' => $validator->errors()->first()
                ], 422);
            }

            $results = [];

            if (isset($input[0])) {
                // Bulk insert
                foreach ($input as $item) {
                    $results[] = $this->movieRepository->createMovieRoll($item);
                }
            } else {
                // Single insert
                $results[] = $this->movieRepository->createMovieRoll($input);
            }

            return response()->json([
                'success' => true,
                'message' => 'Process completed',
                'output' => $results,
            ], 200);
        } catch (\Exception $e) {
            $this->logError($request->url(), $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'output' => null
            ], 500);
        }
    }

    public function getAllMovieRoll()
    {
        try {
            $out_data = $this->movieRepository->getMovieRolls();

            $out_put = [
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'data' => $out_data['data'],
            ];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());

            $out_put = [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }

        return response()->json([
            'success' => $out_put['success'],
            'message' => $out_put['message'],
            'output' => $out_put['data'],
        ], 200);
    }

    public function getMovieRollById($roll_id)
    {
        try {
            $out_data = $this->movieRepository->getSingleMovieRoll($roll_id);
            $output['success'] = $out_data['success'];
            $output['message'] = $out_data['message'];
            $output['data'] = $out_data['data'];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        } finally {
            return response()->json($output, 200);
        }
    }

    public function updateMovieRoll(Request $request, $roll_id)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'role' => 'required|string',
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
            $data['roll_id'] = (int) $roll_id;
            $out_data = $this->movieRepository->updateMovieRoll($data);
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

    public function deleteMovieRoll(Request $request, $roll_id)
    {
        try {
            $data = [
                'roll_id' => (int) $roll_id,
                'deleted_by' => 1,
            ];
            $out_data = $this->movieRepository->deleteMovieRoll($data);
            return response()->json([
                'success' => $out_data['success'],
                'message' => $out_data['message'],
                'output' => $out_data['data'],
            ], 200);
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, please try again.',
                'output' => null
            ], 500);
        }
    }

    //Movie Controller

    public function createMovie(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'title' => 'required|string',
                'sub_title' => 'required|string',
                'rate' => 'required|string',
                'quality' => 'required|string',
                'duration' => 'required|string',
                'country' => 'required|string',
                'language_id' => 'required|integer',
                'category_id' => 'required|integer',
                'year' => 'required|string',
                'subtitle_by' => 'required|string',
                'description' => 'required|string',
                'cover_image' => 'required|string',
                'main_image' => 'required|string',
                'created_by' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'output' => $validator->errors()->first()
                ], 422);
            }
            $data = $request->all();
            $out_data = $this->movieRepository->createMovie($data);
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

    public function getMovieById($movie_id)
    {
        try {
            $out_data = $this->movieRepository->getSingleMovie($movie_id);
            $output['success'] = $out_data['success'];
            $output['message'] = $out_data['message'];
            $output['data'] = $out_data['data'];
        } catch (\Exception $e) {
            $this->logError(request()->url(), $e->getMessage());
            $output['success'] = false;
            $output['message'] = 'Something went wrong, please try again: ' . $e->getMessage();
            $output['data'] = null;
        } finally {
            return response()->json($output, 200);
        }
    }


    public function updateMovie(Request $request, $movie_id)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'title' => 'required|string',
                'sub_title' => 'required|string',
                'rate' => 'required|string',
                'quality' => 'required|string',
                'duration' => 'required|string',
                'country' => 'required|string',
                'language_id' => 'required|integer',
                'category_id' => 'required|integer',
                'year' => 'required|string',
                'subtitle_by' => 'required|string',
                'description' => 'required|string',
                'cover_image' => 'required|string',
                'main_image' => 'required|string',
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
            $data['movie_id'] = $movie_id;
            $out_data = $this->movieRepository->updateMovie($data);
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

    public function deleteMovie($movie_id)
    {
        try {
            $out_data = $this->movieRepository->deleteMovie($movie_id);

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

    public function uploadFilesToCloudinary(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'folder' => 'required|string',
                'file' => 'required|file',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            if ($file->getSize() > 10 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'File size exceeded. Maximum allowed size is 10MB.',
                    'data' => null
                ], 413); // 413 Payload Too Large
            }

            $timestamp = time();

            $signatureParams = [
                'folder' => $request->folder,
                'timestamp' => $timestamp,
                'upload_preset' => $this->preset,
            ];

            ksort($signatureParams);

            $signatureString = collect($signatureParams)
                ->map(fn($v, $k) => "$k=$v")
                ->implode('&');

            $signature = sha1($signatureString . $this->apiSecret);

            $postData = [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'upload_preset' => $this->preset,
                'signature' => $signature,
                'folder' => $request->folder,
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                $response = Http::timeout(120)
                    ->withOptions(['stream' => true])
                    ->attach(
                        'file',
                        file_get_contents($file->getRealPath()),
                        $file->getClientOriginalName()
                    )->post("{$this->url}/{$this->cloudName}/raw/upload", $postData);


            } elseif (filter_var($request->file, FILTER_VALIDATE_URL)) {
                $postData['file'] = $request->file;

                $response = Http::asForm()->post("{$this->url}/{$this->cloudName}/raw/upload", $postData);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file input. Provide a valid file or a URL.',
                    'data' => null
                ], 422);
            }

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'data' => [
                        'secure_url' => $response['secure_url'],
                        'public_id' => $response['public_id'],
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'data' => $response->body(),
            ], $response->status());

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 413);
        }
    }


}
