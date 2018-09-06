<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Soup
 *
 * @ORM\Table(name="soup")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SoupRepository")
 */
class Soup implements \JsonSerializable
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
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="create_time", type="string", length=255)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="img_link", type="text")
     */
    private $imgLink;

    public function __construct($id, $title, $content, $createTime, $imgLink)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->createTime = $createTime;
        $this->imgLink = $imgLink;
    }
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'createTime' => $this->createTime,
            'imgLink' => $this->imgLink
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
     * @return Soup
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
     * Set content
     *
     * @param string $content
     *
     * @return Soup
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     *
     * @return Soup
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
     * Set imgLink
     *
     * @param string $imgLink
     *
     * @return Soup
     */
    public function setImgLink($imgLink)
    {
        $this->imgLink = $imgLink;

        return $this;
    }

    /**
     * Get imgLink
     *
     * @return string
     */
    public function getimgLink()
    {
        return $this->imgLink;
    }
}

