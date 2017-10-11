<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 01:12
 */

namespace Leo108\WorkWechat\Message\SendTypes;


abstract class BaseMessage
{
    /**
     * @var array
     */
    protected $toUsers = [];
    /**
     * @var array
     */
    protected $toTags = [];
    /**
     * @var array
     */
    protected $toParties = [];

    /**
     * BaseMessage constructor.
     * @param array $toUsers
     * @param array $toTags
     * @param array $toParties
     */
    public function __construct(array $toUsers = [], array $toTags = [], array $toParties = [])
    {
        $this->toUsers   = $toUsers;
        $this->toTags    = $toTags;
        $this->toParties = $toParties;
    }

    /**
     * @return array
     */
    public function getToUsers()
    {
        return $this->toUsers;
    }

    /**
     * @param array $toUsers
     * @return $this
     */
    public function setToUsers($toUsers)
    {
        $this->toUsers = $toUsers;

        return $this;
    }

    /**
     * @param string|array $user
     * @return $this
     */
    public function addToUsers($user)
    {
        $this->toUsers = array_merge($this->toUsers, (array)$user);

        return $this;
    }

    /**
     * @return array
     */
    public function getToTags()
    {
        return $this->toTags;
    }

    /**
     * @param array $toTags
     */
    public function setToTags($toTags)
    {
        $this->toTags = $toTags;
    }

    /**
     * @param string|array $tag
     * @return $this
     */
    public function addToTags($tag)
    {
        $this->toTags = array_merge($this->toTags, (array)$tag);

        return $this;
    }

    /**
     * @return array
     */
    public function getToParties()
    {
        return $this->toParties;
    }

    /**
     * @param array $toParties
     */
    public function setToParties($toParties)
    {
        $this->toParties = $toParties;
    }

    /**
     * @param string|array $party
     * @return $this
     */
    public function addToParties($party)
    {
        $this->toParties = array_merge($this->toParties, (array)$party);

        return $this;
    }

    /**
     * @param integer $agentId
     * @return array
     */
    public function toArray($agentId)
    {
        return array_merge([
            'touser'  => join('|', $this->toUsers),
            'toparty' => join('|', $this->toParties),
            'totag'   => join('|', $this->toTags),
            'msgtype' => $this->getMessageType(),
            'agentid' => $agentId,
        ], $this->getExtraParams());
    }

    /**
     * @return string
     */
    abstract public function getMessageType();

    /**
     * @return array
     */
    abstract public function getExtraParams();
}
