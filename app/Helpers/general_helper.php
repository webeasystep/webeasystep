<?php

/**
 * Untuk mendapatkan semua nama kolom dalam tabel database
 * @param string $table
 *
 * @return array
 */
function allfields($table): array
{
    $db      = db_connect();
    $query = $db->query("SHOW COLUMNS FROM " . $table . ";")->getResultArray();
    $fields = [];
    foreach ($query as $q) {
        array_push($fields, $q['Field']);
    }
    return $fields;
}

function encrypt_url($p): string
{
    $encrypter = \Config\Services::encrypter();
    return bin2hex($encrypter->encrypt($p));
}

function decrypt_url($p): string
{
    $encrypter = \Config\Services::encrypter();
    return $encrypter->decrypt(hex2bin($p));
}

function getToken()
{
    $request = service('request');
    $getToken = $request->getHeaderLine('Authorization');
    $token = substr($getToken, 7);

    return $token;
}

function getAuthGroup(): array
{
    $db = db_connect();
    $query = $db->table('auth_groups')->get()->getResultArray();

    $group = array();
    foreach ($query as $q) {
        $group[$q['group_name']] = [
            'title' => $q['title'],
            'description' => $q['description']
        ];
    }

    return $group;
}

function getPermission(): array
{
    $db = db_connect();
    $query = $db->table('auth_permission')->get()->getResultArray();

    $permission = array();
    foreach ($query as $q) {
        $permission[$q['authorization']] = $q['description'];
    }

    return $permission;
}

function getPermissionGroup(): array
{
    $db = db_connect();
    $query = $db->table('auth_permission_groups')->select('group_name, authorization')
        ->join('auth_groups', 'auth_groups.id=auth_permission_groups.group_id')
        ->join('auth_permission', 'auth_permission.id=auth_permission_groups.permission')
        ->get()->getResultArray();

    $permission_group = array();
    foreach ($query as $q) {
        $permission_group[$q['group_name']][] = $q['authorization'];
    }

    return $permission_group;
}
