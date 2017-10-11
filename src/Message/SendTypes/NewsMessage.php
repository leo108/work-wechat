<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 2017/10/11
 * Time: 09:20
 */

namespace Leo108\WorkWechat\Message\SendTypes;

class NewsMessage extends BaseMessage
{
    /**
     * @var array
     */
    protected $articles;

    /**
     * NewsMessage constructor.
     * @param       $articles
     * @param array $toUsers
     * @param array $toTags
     * @param array $toParties
     */
    public function __construct($articles = [], array $toUsers = [], array $toTags = [], array $toParties = [])
    {
        parent::__construct($toUsers, $toTags, $toParties);
        $this->articles = $articles;
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
     * @param string $url
     * @param string $description
     * @param string $picurl
     * @param string $btntxt
     * @return $this
     */
    public function addArticle($title, $url, $description = '', $picurl = '', $btntxt = '')
    {
        $data = compact('title', 'url');
        foreach (['description', 'picurl', 'btntxt'] as $field) {
            if ($$field) {
                $data[$field] = $$$field;
            }
        }
        $this->articles[] = $data;

        return $this;
    }

    public function getMessageType()
    {
        return 'news';
    }

    public function getExtraParams()
    {
        return ['news' => ['articles' => $this->articles]];
    }
}
