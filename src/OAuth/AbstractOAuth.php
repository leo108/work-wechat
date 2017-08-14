<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/8/13
 * Time: 12:57
 */

namespace Leo108\WorkWechat\OAuth;

use Leo108\WorkWechat\Core\BaseApi;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractOAuth extends BaseApi
{
    const API_GET_USER_INFO = 'user/getuserinfo';

    /**
     * The HTTP request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectUrl;

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ',';

    /**
     * The type of the encoding in the query.
     *
     * @var int Can be either PHP_QUERY_RFC3986 or PHP_QUERY_RFC1738
     */
    protected $encodingType = PHP_QUERY_RFC1738;

    /**
     * Indicates if the session state should be utilized.
     *
     * @var bool
     */
    protected $stateless = false;

    /**
     * Get the authentication URL for the provider.
     *
     * @param string $state
     *
     * @return string
     */
    abstract protected function getAuthUrl($state);

    /**
     * Redirect the user of the application to the provider's authentication screen.
     *
     * @param string $redirectUrl
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($redirectUrl = null)
    {
        $state = null;

        if (!is_null($redirectUrl)) {
            $this->redirectUrl = $redirectUrl;
        }

        if (!$this->isStateless()) {
            $state = $this->makeState();
        }

        return new RedirectResponse($this->getAuthUrl($state));
    }

    public function getUserByCode()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
        }

        return static::parseJson($this->apiGet(self::API_GET_USER_INFO, ['code' => $this->getCode()]));
    }

    /**
     * Set redirect url.
     *
     * @param string $redirectUrl
     *
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * Return the redirect url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Set the scopes of the requested access.
     *
     * @param array $scopes
     *
     * @return $this
     */
    public function scopes(array $scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Set the request instance.
     *
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the request instance.
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request ?: Request::createFromGlobals();
    }

    /**
     * Indicates that the provider should operate as stateless.
     *
     * @param boolean $stateless
     * @return $this
     */
    public function stateless($stateless = true)
    {
        $this->stateless = $stateless;

        return $this;
    }

    /**
     * Format the scopes.
     *
     * @return string
     */
    protected function getFormattedScopes()
    {
        return implode($this->scopeSeparator, $this->scopes);
    }

    /**
     * Determine if the current request / session has a mismatching "state".
     *
     * @return bool
     */
    protected function hasInvalidState()
    {
        if ($this->isStateless()) {
            return false;
        }

        $state = $this->request->getSession()->get('state');

        return !(strlen($state) > 0 && $this->request->get('state') === $state);
    }

    /**
     * Get the code from the request.
     *
     * @return string
     */
    protected function getCode()
    {
        return $this->request->get('code');
    }

    /**
     * Determine if the provider is operating as stateless.
     *
     * @return bool
     */
    protected function isStateless()
    {
        return $this->stateless;
    }

    /**
     * Put state to session storage and return it.
     *
     * @return string|bool
     */
    protected function makeState()
    {
        $state   = sha1(uniqid(mt_rand(1, 1000000), true));
        $session = $this->request->getSession();

        if (is_callable([$session, 'put'])) {
            $session->put('state', $state);
        } elseif (is_callable([$session, 'set'])) {
            $session->set('state', $state);
        } else {
            return false;
        }

        return $state;
    }

    protected function buildQuery($queries)
    {
        return http_build_query($queries, '', '&', $this->encodingType);
    }
}
