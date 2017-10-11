<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 09:32
 */

namespace Leo108\WorkWechat\Message;

use Leo108\WorkWechat\Core\BaseApi;
use Leo108\WorkWechat\Message\SendTypes\BaseMessage;

class Message extends BaseApi
{
    const API_SEND = 'message/send';

    public function send(BaseMessage $message)
    {
        $agentId = $this->getAgent()->getConfig('agent_id');

        return static::parseJson($this->apiJson(self::API_SEND, $message->toArray($agentId)));
    }
}
