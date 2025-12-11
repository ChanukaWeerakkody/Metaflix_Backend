<?php

namespace App\Repositories;

use App\Models\MobileConfirmation;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\Log;
use function Pest\Laravel\get;
use App\Models\Permission;

class UserRepository implements UserRepositoryInterface
{
    public function encryptText($text) {
        $encrypt_key = env('ENCRYPT_KEY');
        $encrypt_code = env('ENCRYPT_CODE');
        $encryptedText = openssl_encrypt($text, 'AES-128-CBC', $encrypt_key, OPENSSL_RAW_DATA, $encrypt_code);
        if($encryptedText == '' || $encryptedText == null) {
            return $text;
        }
        return base64_encode($encryptedText);
    }
    public function decryptText($encryptedText) {
        $encrypt_key = env('ENCRYPT_KEY');
        $encrypt_code = env('ENCRYPT_CODE');
        $text = $encryptedText;
        $encryptedText = base64_decode($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $encrypt_key, OPENSSL_RAW_DATA, $encrypt_code);
        if($decryptedText == '' || $decryptedText == null) {
            return $text;
        }
        return $decryptedText;
    }

    protected function logError($url, $error_message)
    {
        Log::error('Error in setting repository function', [
            'url' => $url,
            'error' => $error_message,
        ]);
    }

    public function addSystemRole(array $params)
    {
        try {
            $role = $params['role'] ?? null;

            if (! $role) {
                return [
                    'success' => false,
                    'message' => 'role is required.',
                    'data' => null,
                ];
            }

            $role = Role::create([
                'role' => $role,
                'is_active' => 1
            ]);

            if($role) {
                return [
                    'success' => true,
                    'message' => 'Role added successfully.',
                    'data' => [
                        'role_id' => $role->id,
                    ]
                    ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Role added unsuccessfully.',
                    'data' => null
                ];
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function editSystemRole(array $params)
    {
        try {
            $role_id = (int) ($params['role_id'] ?? 0);
            $role = $params['role'] ?? null;

            if (!$role || $role_id <= 0) {
                return [
                    'success' => false,
                    'message' => 'role_id and role are required.',
                    'data' => null,
                ];
            }

            $role_exists = Role::where('is_active', 1)
                ->where('role', $role)
                ->first();

            if($role_exists) {
                return [
                    'success' => false,
                    'message' => 'Role already exists.',
                    'data' => null
                ];
            } else {
                $role_db = Role::where('id',$role_id)
                    ->where('is_active', 1)
                    ->first();

                if ($role) {
                    $role_db->role = $role;
                    $role_db->save();

                    return [
                        'success' => true,
                        'message' => 'Role updated successfully.',
                        'data' => [
                            'role_id' => $role_id,
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Role not found.',
                        'data' => null
                    ];
                }
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getSystemRoles(array $params)
    {
        try {
            $roles = Role::where('is_active', 1)
                ->get();

            if ($roles->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'Roles fetched successfully.',
                    'data' => null
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Roles fetched successfully.',
                    'data' => $roles->toArray()
                ];
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteSystemRole(array $params)
    {
        try {
            $role_id = (int) ($params['role_id'] ?? 0);

            if ($role_id <= 0) {
                return [
                    'success' => false,
                    'message' => 'role_id is required.',
                    'data' => null,
                ];
            }

            $role = Role::where('id', $role_id)
                ->where('is_active', 1)
                ->first();

            if ($role) {
                $role->is_active = 0;
                $role->save();

                return [
                    'success' => true,
                    'message' => 'Role deleted successfully.',
                    'data' => [
                        'role_id' => $role_id,
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Role not found.',
                    'data' => null
                ];
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function addPermission(array $params)
    {
        try {
            $role_id = (int) ($params['role_id'] ?? 0);
            $permission = $params['permission'] ?? null;
            $permission_key = $params['permission_key'] ?? null;

            if (!$role_id || !$permission || !$permission_key) {
                return [
                    'success' => false,
                    'message' => 'role_id, permission and permission_key are required.',
                    'data' => null,
                ];
            }

            $permission = Permission::create([
                'role_id' => $role_id,
                'permission' => $permission,
                'permission_key' => $permission_key,
                'is_active' => 1
            ]);

            if ($permission) {
                return [
                    'success' => true,
                    'message' => 'Permission added successfully.',
                    'data' => [
                        'permission_id' => $permission->id,
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Permission added unsuccessfully.',
                    'data' => null
                ];
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function editPermission(array $params)
    {
        try {
            $role_id = (int) ($params['role_id'] ?? 0);
            $permission_id = (int) ($params['permission_id'] ?? 0);
            $permission = $params['permission'] ?? null;
            $permission_key = $params['permission_key'] ?? null;

            if (!$permission_id || !$permission || !$permission_key) {
                return [
                    'success' => false,
                    'message' => 'permission_id, permission and permission_key are required.',
                    'data' => null,
                ];
            }

            $permission_exists = Permission::where('is_active', 1)
                ->where('permission', $permission)
                ->where('id', '!=', $permission_id)
                ->first();

            if ($permission_exists) {
                return [
                    'success' => false,
                    'message' => 'Permission already exists.',
                    'data' => null
                ];
            } else {
                $permission_db = Permission::where('id', $permission_id)
                    ->where('is_active', 1)
                    ->first();

                if ($permission_db) {
                    $permission_db->role_id = $role_id;
                    $permission_db->permission = $permission;
                    $permission_db->permission_key = $permission_key;
                    $permission_db->save();

                    return [
                        'success' => true,
                        'message' => 'Permission updated successfully.',
                        'data' => [
                            'permission_id' => $permission_id,
                        ]
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Permission not found.',
                        'data' => null
                    ];
                }
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getPermissions(array $params)
    {
        try {
            $permissions = Permission::where('is_active', 1)
                ->get();

            if ($permissions->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'Permissions fetched successfully.',
                    'data' => null
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Permissions fetched successfully.',
                    'data' => $permissions->toArray()
                ];
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deletePermission(array $params)
    {
        try {
            $permission_id = (int) ($params['permission_id'] ?? 0);

            if ($permission_id <= 0) {
                return [
                    'success' => false,
                    'message' => 'permission_id is required.',
                    'data' => null,
                ];
            }

            $permission = Permission::where('id', $permission_id)
                ->where('is_active', 1)
                ->first();

            if ($permission) {
                $permission->is_active = 0;
                $permission->save();

                return [
                    'success' => true,
                    'message' => 'Permission deleted successfully.',
                    'data' => [
                        'permission_id' => $permission_id,
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Permission not found.',
                    'data' => null
                ];
            }
        } catch (\Throwable $e) {
            if (!empty($params['url'])) {
                $this->logError($params['url'], $e->getMessage());
            }
            return [
                'success' => false,
                'message' => 'Something went wrong, please try again: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}