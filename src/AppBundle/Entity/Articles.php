<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticlesRepository")
 */
class Articles implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content_html", type="text")
     */
    private $contentHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="create_time", type="string", length=255)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="belong_class", type="string", length=255)
     */
    private $belongClass;

    /**
     * @var string
     *
     * @ORM\Column(name="introduction_text", type="string", length=255)
     */
    private $introductionText;


    public function __construct($id, $title, $contentHtml, $createTime, $belongClass, $introductionText) {
        $this->id = $id;
        $this->title = $title;
        $this->contentHtml = $contentHtml;
        $this->createTime = $createTime;
        $this->belongClass = $belongClass;
        $this->introductionText = $introductionText;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'contentHtml' => $this->contentHtml,
            'createTime' => $this->createTime,
            'belongClass' => $this->belongClass,
            'introductionText' => $this->introductionText,
        );
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Articles
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set contentHtml
     *
     * @param string $contentHtml
     *
     * @return Articles
     */
    public function setContentHtml($contentHtml)
    {
        $this->contentHtml = $contentHtml;

        return $this;
    }

    /**
     * Get contentHtml
     *
     * @return string
     */
    public function getContentHtml()
    {
        return $this->contentHtml;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     *
     * @return Articles
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set belongClass
     *
     * @param string $belongClass
     *
     * @return Articles
     */
    public function setBelongClass($belongClass)
    {
        $this->belongClass = $belongClass;

        return $this;
    }

    /**
     * Get belongClass
     *
     * @return string
     */
    public function getBelongClass()
    {
        return $this->belongClass;
    }

    /**
     * Set introductionText
     *
     * @param string $introductionText
     *
     * @return Articles
     */
    public function setIntroductionText($introductionText)
    {
        $this->introductionText = $introductionText;

        return $this;
    }

    /**
     * Get introductionText
     *
     * @return string
     */
    public function getIntroductionText()
    {
        return $this->introductionText;
    }
}
