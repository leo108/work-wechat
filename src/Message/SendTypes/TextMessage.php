<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 01:15
 */

namespace Leo108\WorkWechat\Message\SendTypes;

class TextMessage extends BaseMessage
{
    /**
     * @var string
     */
    protected $content;
    /**
     * @var bool
     */
    protected $safe = false;

    /**
     * TextMessage constructor.
     * @param string $content
     * @param bool   $safe
     * @param array  $toUsers
     * @param array  $toTags
     * @param array  $toParties
     */
    public function __construct($content, $safe = false, array $toUsers = [], array $toTags = [], array $toParties = [])
    {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->content = $content;
        $this->safe    = $safe;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return bool
     */
    public function isSafe()
    {
        return $this->safe;
    }

    /**
     * @param bool $safe
     */
    public function setSafe($safe)
    {
        $this->safe = $safe;
    }

    public function getMessageType()
    {
        return 'text';
    }

    public function getExtraParams()
    {
        return [
            'text' => [
                'content' => $this->content,
            ],
            'safe' => $this->safe ? 1 : 0,
        ];
    }
}
