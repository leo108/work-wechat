<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 01:28
 */

namespace Leo108\WorkWechat\Message\SendTypes;


class ImageMessage extends BaseMessage
{
    /**
     * @var string
     */
    protected $mediaId;
    /**
     * @var bool
     */
    protected $safe = false;

    /**
     * TextMessage constructor.
     * @param string $mediaId
     * @param bool   $safe
     * @param array  $toUsers
     * @param array  $toTags
     * @param array  $toParties
     */
    public function __construct($mediaId, $safe = false, array $toUsers = [], array $toTags = [], array $toParties = [])
    {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->mediaId = $mediaId;
        $this->safe    = $safe;
    }

    /**
     * @return string
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * @param string $mediaId
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
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
        return 'image';
    }

    public function getExtraParams()
    {
        return [
            'image' => [
                'media_id' => $this->mediaId,
            ],
            'safe'  => $this->safe ? 1 : 0,
        ];
    }
}
