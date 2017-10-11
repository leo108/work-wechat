<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 01:31
 */

namespace Leo108\WorkWechat\Message\SendTypes;

class VideoMessage extends BaseMessage
{
    /**
     * @var string
     */
    protected $mediaId;
    /**
     * @var string
     */
    protected $title = '';
    /**
     * @var string
     */
    protected $description = '';
    /**
     * @var bool
     */
    protected $safe = false;

    /**
     * TextMessage constructor.
     * @param string $mediaId
     * @param string $title
     * @param string $description
     * @param bool   $safe
     * @param array  $toUsers
     * @param array  $toTags
     * @param array  $toParties
     */
    public function __construct(
        $mediaId,
        $title = '',
        $description = '',
        $safe = false,
        array $toUsers = [],
        array $toTags = [],
        array $toParties = []
    ) {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->mediaId     = $mediaId;
        $this->title       = $title;
        $this->description = $description;
        $this->safe        = $safe;
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

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getMessageType()
    {
        return 'video';
    }

    public function getExtraParams()
    {
        return [
            'video' => [
                'media_id'    => $this->mediaId,
                'title'       => $this->title,
                'description' => $this->description,
            ],
            'safe'  => $this->safe ? 1 : 0,
        ];
    }
}
