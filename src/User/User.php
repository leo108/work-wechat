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
    const API_GET_USER = 'user/get';

    public function getUser($userId)
    {
        return static::parseJson($this->apiGet(self::API_GET_USER, ['userid' => $userId]));
    }
}
