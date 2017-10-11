<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 01:29
 */

namespace Leo108\WorkWechat\Message\SendTypes;


class VoiceMessage extends BaseMessage
{
    /**
     * @var string
     */
    protected $mediaId;

    /**
     * TextMessage constructor.
     * @param string $mediaId
     * @param array  $toUsers
     * @param array  $toTags
     * @param array  $toParties
     */
    public function __construct($mediaId, array $toUsers = [], array $toTags = [], array $toParties = [])
    {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->mediaId = $mediaId;
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

    public function getMessageType()
    {
        return 'voice';
    }

    public function getExtraParams()
    {
        return [
            'voice' => [
                'media_id' => $this->mediaId,
            ],
        ];
    }
}
