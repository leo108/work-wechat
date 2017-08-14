<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/14
 * Time: 11:21
 */

namespace Leo108\WorkWechat\OAuth;

class OAuth extends AbstractOAuth
{
    const API_GET_USER_DETAIL = 'user/getuserdetail';

    protected function getAuthUrl($state)
    {
        $queries = [
            'appid'         => $this->getAgent()->getWechat()->getCorpId(),
            'redirect_uri'  => $this->getRedirectUrl(),
            'response_type' => 'code',
            'scope'         => $this->getFormattedScopes(),
            'agentid'       => $this->getAgent()->getConfig('agent_id'),
            'state'         => $state,
        ];

        return 'https://open.weixin.qq.com/connect/oauth2/authorize?'.$this->buildQuery($queries).'#wechat_redirect';
    }

    public function getUserDetailByTicket($ticket)
    {
        return $this->parseJson($this->apiJson(self::API_GET_USER_DETAIL.'?'.http_build_query([
                'access_token' => $this->getAgent()->accessToken->getToken(),
            ]), [
            'user_ticket' => $ticket,
        ]));
    }

    public function getUserDetail()
    {
        $user = $this->getUserByCode();

        return $this->getUserDetailByTicket($user['user_ticket']);
    }
}
