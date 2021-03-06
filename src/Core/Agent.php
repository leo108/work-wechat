<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/11
 * Time: 22:03
 */

namespace Leo108\WorkWechat\Core;

use GuzzleHttp\ClientInterface;
use Leo108\SDK\SDK;
use Leo108\WorkWechat\Contact\Department;
use Leo108\WorkWechat\Message\Message;
use Leo108\WorkWechat\OAuth\OAuth;
use Leo108\WorkWechat\OAuth\QRConnect;
use Leo108\WorkWechat\Contact\User;
use Leo108\WorkWechat\WorkWechat;
use Psr\Log\LoggerInterface;

/**
 * Class Agent
 * @package Leo108\WorkWechat
 * @property \Leo108\WorkWechat\Core\AccessToken   $accessToken
 * @property \Leo108\WorkWechat\OAuth\OAuth        $oauth
 * @property \Leo108\WorkWechat\OAuth\QRConnect    $qrConnect
 * @property \Leo108\WorkWechat\Contact\User       $user
 * @property \Leo108\WorkWechat\Contact\Department $department
 * @property \Leo108\WorkWechat\Message\Message    $message
 */
class Agent extends SDK
{
    /**
     * @var WorkWechat
     */
    protected $wechat;

    public function __construct(
        WorkWechat $wechat,
        array $config = [],
        ClientInterface $httpClient = null,
        LoggerInterface $logger = null
    ) {
        parent::__construct($config, $httpClient, $logger);
        $this->wechat = $wechat;
    }

    protected function getApiMap()
    {
        return [
            'accessToken' => AccessToken::class,
            'oauth'       => OAuth::class,
            'qrConnect'   => QRConnect::class,
            'user'        => User::class,
            'department'  => Department::class,
            'message'     => Message::class,
        ];
    }

    /**
     * @return WorkWechat
     */
    public function getWechat()
    {
        return $this->wechat;
    }

    public function __get($name)
    {
        return parent::__get($name);
    }
}
