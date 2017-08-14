<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/14
 * Time: 11:18
 */

namespace Leo108\WorkWechat\OAuth;

class QRConnect extends AbstractOAuth
{
    protected function getAuthUrl($state)
    {
        $queries = [
            'appid'        => $this->getAgent()->getWechat()->getCorpId(),
            'redirect_uri' => $this->getRedirectUrl(),
            'agentid'      => $this->getAgent()->getConfig('agent_id'),
            'state'        => $state,
        ];

        return 'https://open.work.weixin.qq.com/wwopen/sso/qrConnect?'.$this->buildQuery($queries);
    }
}
