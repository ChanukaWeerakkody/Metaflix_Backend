<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function encryptText($data);
    public function decryptText($data);

    public function addSystemRole(array $params);
    public function editSystemRole(array $params);
    public function getSystemRoles(array $params);
    public function deleteSystemRole(array $params);

    public function addPermission(array $params);
    public function editPermission(array $params);
    public function getPermissions(array $params);
    public function deletePermission(array $params);
}