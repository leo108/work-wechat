<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/11
 * Time: 22:06
 */

namespace Leo108\WorkWechat\Core;

use Leo108\SDK\AbstractApi;
use Leo108\SDK\Middleware\TokenMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
    protected function parseJson(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();

        return \GuzzleHttp\json_decode($content, true);
    }

    public function handleNotify(callable $handler, Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::createFromGlobals();
        }
        // todo
    }

    protected function getHttpMiddleware()
    {
        return array_filter([
            $this->getLogRequestMiddleware(),
            $this->getTokenMiddleware(),
            $this->getRetryMiddleware(),
        ]);
    }


    protected function getLogRequestMiddleware()
    {

    }

    protected function getTokenMiddleware()
    {
        return new TokenMiddleware(true, function (RequestInterface $request) {
            return $this->attachAccessToken($request);
        });
    }

    protected function getRetryMiddleware()
    {
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
        $query['access_token'] = $this->getAgent()->accessToken->getToken($cache);
        $uri                   = $request->getUri()->withQuery(http_build_query($query));

        return $request->withUri($uri);
    }
}
