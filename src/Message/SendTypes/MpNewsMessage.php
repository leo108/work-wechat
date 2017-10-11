<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 09:28
 */

namespace Leo108\WorkWechat\Message\SendTypes;

class MpNewsMessage extends BaseMessage
{
    /**
     * @var array
     */
    protected $articles;
    /**
     * @var bool
     */
    protected $safe = false;

    /**
     * NewsMessage constructor.
     * @param array $articles
     * @param bool  $safe
     * @param array $toUsers
     * @param array $toTags
     * @param array $toParties
     */
    public function __construct(
        $articles = [],
        $safe = false,
        array $toUsers = [],
        array $toTags = [],
        array $toParties = []
    ) {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->articles = $articles;
        $this->safe     = $safe;
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
     * @return array
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param array $articles
     * @return $this
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;

        return $this;
    }

    /**
     * @param $article
     * @return $this
     */
    public function addArticles($article)
    {
        $this->articles = array_merge($this->articles, (array)$article);

        return $this;
    }

    /**
     * @param string $title
     * @param string $thumb_media_id
     * @param string $content
     * @param string $author
     * @param string $content_source_url
     * @param string $digest
     * @return $this
     */
    public function addArticle($title, $thumb_media_id, $content, $author = '', $content_source_url = '', $digest = '')
    {
        $data = compact('title', 'thumb_media_id', 'content');
        foreach (['author', 'content_source_url', 'digest'] as $field) {
            if ($$field) {
                $data[$field] = $$$field;
            }
        }
        $this->articles[] = $data;

        return $this;
    }

    public function getMessageType()
    {
        return 'mpnews';
    }

    public function getExtraParams()
    {
        return [
            'mpnews' => ['articles' => $this->articles],
            'safe'   => $this->safe ? 1 : 0,
        ];
    }
}
