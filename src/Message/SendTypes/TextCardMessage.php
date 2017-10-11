<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 01:35
 */

namespace Leo108\WorkWechat\Message\SendTypes;


class TextCardMessage extends BaseMessage
{
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $btnText;

    /**
     * TextMessage constructor.
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $btnText
     * @param array  $toUsers
     * @param array  $toTags
     * @param array  $toParties
     */
    public function __construct(
        $title,
        $description,
        $url,
        $btnText = '',
        array $toUsers = [],
        array $toTags = [],
        array $toParties = []
    ) {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->title       = $title;
        $this->description = $description;
        $this->url         = $url;
        $this->btnText     = $btnText;
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

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getBtnText()
    {
        return $this->btnText;
    }

    /**
     * @param string $btnText
     */
    public function setBtnText($btnText)
    {
        $this->btnText = $btnText;
    }

    public function getMessageType()
    {
        return 'textcard';
    }

    public function getExtraParams()
    {
        $data = [
            'textcard' => [
                'title'       => $this->title,
                'description' => $this->description,
                'url'         => $this->url,
            ],
        ];
        if ($this->btnText) {
            $data['textcard']['btntxt'] = $this->btnText;
        }

        return $data;
    }
}
