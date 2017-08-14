<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/11
 * Time: 21:46
 */

namespace Leo108\WorkWechat\Core;

use Leo108\WorkWechat\Core\Exceptions\GetAccessTokenException;

class AccessToken extends BaseApi
{
    const API_GET_TOKEN = 'gettoken';

    public function getToken($refresh = false)
    {
        $cacheKey = $this->getCacheKey();
        $cache    = $this->getAgent()->getWechat()->getCache();
        if ($refresh || !$ret = $cache->get($cacheKey)) {
            $token = $this->getTokenFromServer();
            $cache->set($cacheKey, $token['access_token'], $token['expires_in'] - 1500);

            return $token['access_token'];
        }

        return $ret;
    }

    public function getTokenFromServer()
    {
        $ret = $this->parseJson($this->apiGet(self::API_GET_TOKEN, [
            'corpid'     => $this->getAgent()->getWechat()->getCorpId(),
            'corpsecret' => $this->getAgent()->getConfig('secret'),
        ]));
        if (empty($ret['access_token'])) {
            throw new GetAccessTokenException('get AccessToken fail. response: '.json_encode($ret));
        }

        return $ret;
    }

    public function getCacheKey()
    {
        $prefix  = $this->getAgent()->getWechat()->getCacheKeyPrefix();
        $corpId  = $this->getAgent()->getWechat()->getCorpId();
        $agentId = $this->getAgent()->getConfig('agent_id');

        return sprintf('%s.access_token.%s.%s', $prefix, $corpId, $agentId);
    }

    protected function getTokenMiddleware()
    {
        // disable token middleware
        return null;
    }
}
