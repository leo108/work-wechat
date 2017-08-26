<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/26
 * Time: 09:22
 */

namespace Leo108\WorkWechat\Core;

use Leo108\WorkWechat\Department\Department;
use Leo108\WorkWechat\User\User;

class ContactAgent extends Agent
{
    protected function getApiMap()
    {
        return [
            'user'       => User::class,
            'department' => Department::class,
        ];
    }
}
