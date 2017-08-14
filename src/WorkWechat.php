<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/11
 * Time: 21:45
 */

namespace Leo108\WorkWechat;

use Cache\Adapter\PHPArray\ArrayCachePool;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Leo108\WorkWechat\Core\Agent;
use Leo108\WorkWechat\Core\Exceptions\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

class WorkWechat
{
    /**
     * @var string
     */
    protected $corpId;

    /**
     * @var CacheInterface
     */
    protected $cache = null;

    /**
     * @var LoggerInterface
     */
    protected $logger = null;

    /**
     * @var ClientInterface
     */
    protected $httpClient = null;

    /**
     * @var Agent[]
     */
    protected $agents;

    /**
     * @var string
     */
    protected $cacheKeyPrefix = 'work_wechat';

    /**
     * WorkWechat constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->parseConfig($config);
    }

    /**
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return null|LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param null|LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        foreach ($this->agents as $agent) {
            $agent->setLogger($logger);
        }
    }

    /**
     * @return ClientInterface|null
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param ClientInterface|null $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
        foreach ($this->agents as $agent) {
            $agent->setHttpClient($httpClient);
        }
    }

    /**
     * @return string
     */
    public function getCorpId()
    {
        return $this->corpId;
    }

    /**
     * @return string
     */
    public function getCacheKeyPrefix()
    {
        return $this->cacheKeyPrefix;
    }

    /**
     * @return Agent[]
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * @param string $name
     * @return Agent|null
     */
    public function getAgent($name)
    {
        return isset($this->agents[$name]) ? $this->agents[$name] : null;
    }

    protected function parseConfig(array $config)
    {
        if (!isset($config['corp_id'])) {
            throw new InvalidArgumentException('缺少 corp_id 参数');
        }

        $this->corpId = $config['corp_id'];

        if (isset($config['cache_key_prefix'])) {
            $this->cacheKeyPrefix = $config['cache_key_prefix'];
        }

        if (isset($config['cache'])) {
            if (!(is_null($config['cache']) || $config['cache'] instanceof CacheInterface)) {
                throw new InvalidArgumentException('cache 参数必须为 null 或者继承 CacheInterface 的类对象');
            }
            $this->cache = $config['cache'];
        } else {
            $this->cache = new ArrayCachePool();
        }

        if (isset($config['logger'])) {
            if (!(is_null($config['logger']) || $config['logger'] instanceof LoggerInterface)) {
                throw new InvalidArgumentException('logger 参数必须为 null 或者继承 LoggerInterface 的类对象');
            }
            $this->logger = $config['logger'];
        } else {
            $this->logger = new NullLogger();
        }

        if (isset($config['http_client'])) {
            if (!(is_null($config['http_client']) || $config['http_client'] instanceof ClientInterface)) {
                throw new InvalidArgumentException('http_client 参数必须为 null 或者继承 ClientInterface 的类对象');
            }
            $this->httpClient = $config['http_client'];
        } else {
            $this->httpClient = new Client([RequestOptions::HTTP_ERRORS => false]);
        }

        if (isset($config['agents'])) {
            if (!(is_array($config['agents']) || $config['agents'] instanceof \ArrayAccess)) {
                throw new InvalidArgumentException('agents 参数必须为数组');
            }
            foreach ($config['agents'] as $name => $agent) {
                $this->agents[$name] = new Agent($this, $agent, $this->httpClient, $this->logger);
            }
        }
    }
}
