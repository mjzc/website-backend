<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 */
class Song
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
     * @ORM\Column(name="song_name", type="string", length=255)
     */
    private $songName;

    /**
     * @var string
     *
     * @ORM\Column(name="song_url", type="text")
     */
    private $songUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="song_img", type="text")
     */
    private $songImg;

    /**
     * @var string
     *
     * @ORM\Column(name="lyric", type="text")
     */
    private $lyric;

    /**
     * @var int
     *
     * @ORM\Column(name="singer_id", type="integer")
     */
    private $singerId;

    /**
     * @var string
     *
     * @ORM\Column(name="create_time", type="string")
     */
    private $createTime;


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
     * Set songName
     *
     * @param string $songName
     *
     * @return Song
     */
    public function setSongName($songName)
    {
        $this->songName = $songName;

        return $this;
    }

    /**
     * Get songName
     *
     * @return string
     */
    public function getSongName()
    {
        return $this->songName;
    }

    /**
     * Set songUrl
     *
     * @param string $songUrl
     *
     * @return Song
     */
    public function setSongUrl($songUrl)
    {
        $this->songUrl = $songUrl;

        return $this;
    }

    /**
     * Get songUrl
     *
     * @return string
     */
    public function getSongUrl()
    {
        return $this->songUrl;
    }

    /**
     * Set songImg
     *
     * @param string $songImg
     *
     * @return Song
     */
    public function setSongImg($songImg)
    {
        $this->songImg = $songImg;

        return $this;
    }

    /**
     * Get songImg
     *
     * @return string
     */
    public function getSongImg()
    {
        return $this->songImg;
    }

    /**
     * Set lyric
     *
     * @param string $lyric
     *
     * @return Song
     */
    public function setLyric($lyric)
    {
        $this->lyric = $lyric;

        return $this;
    }

    /**
     * Get lyric
     *
     * @return string
     */
    public function getLyric()
    {
        return $this->lyric;
    }

    /**
     * Set singerId
     *
     * @param integer $singerId
     *
     * @return Song
     */
    public function setSingerId($singerId)
    {
        $this->singerId = $singerId;

        return $this;
    }

    /**
     * Get singerId
     *
     * @return int
     */
    public function getSingerId()
    {
        return $this->singerId;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     *
     * @return Song
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
}

