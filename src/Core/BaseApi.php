<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/11
 * Time: 22:06
 */

namespace Leo108\WorkWechat\Core;

use GuzzleHttp\Middleware;
use Leo108\SDK\AbstractApi;
use Leo108\SDK\Middleware\TokenMiddleware;
use Leo108\WorkWechat\Core\Middleware\CheckApiResponseMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Request;

class BaseApi extends AbstractApi
{
    /**
     * @var Agent
     */
    protected $sdk;

    /**
     * @return Agent
     */
    protected function getAgent()
    {
        return $this->sdk;
    }

    protected function getFullApiUrl($api)
    {
        return 'https://qyapi.weixin.qq.com/cgi-bin/'.ltrim($api, '/');
    }

    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public static function parseJson(ResponseInterface $response)
    {
        return \GuzzleHttp\json_decode($response->getBody(), true);
    }

    public function handleNotify(callable $handler, Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }
        // todo
    }

    /**
     * @return array
     */
    protected function getHttpMiddleware()
    {
        return array_filter([
            $this->getCheckApiResponseMiddleware(),
            $this->getRetryMiddleware(),
            $this->getTokenMiddleware(),
            $this->getLogRequestMiddleware(),
        ]);
    }

    /**
     * @return CheckApiResponseMiddleware
     */
    protected function getCheckApiResponseMiddleware()
    {
        return new CheckApiResponseMiddleware(true, [static::class, 'parseJson']);
    }

    /**
     * @return callable
     */
    protected function getLogRequestMiddleware()
    {
        $wechat    = $this->getAgent()->getWechat();
        $logger    = $this->getAgent()->getLogger();
        $hideToken = $wechat->getConfig('log.hide_access_token', true);
        $formatter = new MessageFormatter($wechat->getConfig('log.format', MessageFormatter::CLF), $hideToken);
        $logLevel  = $wechat->getConfig('log.level', LogLevel::INFO);

        return Middleware::log($logger, $formatter, $logLevel);
    }

    /**
     * @return TokenMiddleware
     */
    protected function getTokenMiddleware()
    {
        return new TokenMiddleware(true, function (RequestInterface $request) {
            return $this->attachAccessToken($request);
        });
    }

    /**
     * @return callable
     */
    protected function getRetryMiddleware()
    {
        return Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null) {
            if ($retries >= $this->getAgent()->getWechat()->getConfig('api_retry', 3)) {
                return false;
            }
            if (!$response || $response->getStatusCode() >= 400) {
                return true;
            }

            $ret = static::parseJson($response);
            if (in_array($ret['errcode'], ['40001', '40014', '41001', '42001'])) {
                // 刷新 access token
                $this->getAgent()->accessToken->getToken(true);

                return true;
            }

            return false;
        });
    }

    /**
     * 在请求的 url 后加上 access_token 参数
     *
     * @param RequestInterface $request
     * @param bool             $cache
     *
     * @return RequestInterface
     */
    private function attachAccessToken(RequestInterface $request, $cache = true)
    {
        $query                 = \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery());
        $query['access_token'] = $this->getAgent()->accessToken->getToken(!$cache);
        $uri                   = $request->getUri()->withQuery(http_build_query($query));

        return $request->withUri($uri);
    }
}
