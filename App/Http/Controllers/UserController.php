<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function addSystemRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role' => 'required|string'
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->userRepository->addSystemRole($data);

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

    public function editSystemRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|int',
                'role' => 'required|string'
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->userRepository->editSystemRole($data);

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

    public function getSystemRoles(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->userRepository->getSystemRoles($data);

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

    public function deleteSystemRole(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                $output['success'] = false;
                $output['message'] = "Data didn't passed correctly.";
                $output['data'] = $validator->errors()->first();
            } else {
                $data = json_decode($request->getContent(), true);
                $data['url'] = $request->url();
                $out_data = $this->userRepository->deleteSystemRole($data);

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