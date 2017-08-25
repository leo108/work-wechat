<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/14
 * Time: 14:36
 */

namespace Leo108\WorkWechat\User;

use Leo108\WorkWechat\Core\BaseApi;

class User extends BaseApi
{
    const API_GET = 'user/get';
    const API_CREATE = 'user/create';
    const API_UPDATE = 'user/update';
    const API_DELETE = 'user/delete';
    const API_BATCH_DELETE = 'user/batchdelete';
    const API_SIMPLE_LIST = 'user/simplelist';
    const API_LIST = 'user/list';
    const API_CONVERT_TO_OPENID = 'user/convert_to_openid';
    const API_AUTH_SUCC = 'user/authsucc';

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    public function get($userId)
    {
        return static::parseJson($this->apiGet(self::API_GET, ['userid' => $userId]));
    }

    public function create($data)
    {
        return static::parseJson($this->apiJson(self::API_CREATE, $data));
    }

    public function update($userId, $data)
    {
        return static::parseJson($this->apiJson(self::API_UPDATE, array_merge($data, ['userid' => $userId])));
    }

    public function delete($userId)
    {
        return static::parseJson($this->apiGet(self::API_DELETE, ['userid' => $userId]));
    }

    public function batchDelete(array $userIdArr)
    {
        return static::parseJson($this->apiJson(self::API_BATCH_DELETE, ['useridlist' => $userIdArr]));
    }

    public function simpleList($departmentId, $fetchChild = false)
    {
        return static::parseJson($this->apiJson(self::API_SIMPLE_LIST, [
            'department_id' => $departmentId,
            'fetch_child'   => $fetchChild ? 1 : 0,
        ]));
    }

    public function userList($departmentId, $fetchChild = false)
    {
        return static::parseJson($this->apiJson(self::API_LIST, [
            'department_id' => $departmentId,
            'fetch_child'   => $fetchChild ? 1 : 0,
        ]));
    }

    public function convertToOpenId($userId, $agentId = null)
    {
        $req = ['userid' => $userId];
        if (!is_null($agentId)) {
            $req['agentid'] = $agentId;
        }

        return static::parseJson($this->apiJson(self::API_CONVERT_TO_OPENID, $req));
    }

    public function authSucc($userId)
    {
        return static::parseJson($this->apiGet(self::API_AUTH_SUCC, ['userid' => $userId]));
    }
}
