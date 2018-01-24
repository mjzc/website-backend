<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuthorRepository")
 */
class Author implements \JsonSerializable
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
     * @ORM\Column(name="account", type="string", length=255, unique=true)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_name", type="string", length=255)
     */
    private $blogName;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_intro", type="string", length=255)
     */
    private $blogIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="blog_head_img", type="string", length=255)
     */
    private $blogHeadImg;

    /**
     * @var string
     *
     * @ORM\Column(name="create_time", type="string", length=255)
     */
    private $createTime;

    public function __construct($account, $blogName, $blogIntro, $blogHeadImg)
    {
        $this->account = $account;
        $this->blogName = $blogName;
        $this->blogIntro = $blogIntro;
        $this->blogHeadImg = $blogHeadImg;
    }
    public function jsonSerialize()
    {
        return array([
           'account'=>$this->account,
           'blogName'=>$this->blogName,
           'blogIntro'=>$this->blogIntro,
           'blogHeadImg'=>$this->blogHeadImg
        ]);
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
     * Set account
     *
     * @param string $account
     *
     * @return Author
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Author
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set blogName
     *
     * @param string $blogName
     *
     * @return Author
     */
    public function setBlogName($blogName)
    {
        $this->blogName = $blogName;

        return $this;
    }

    /**
     * Get blogName
     *
     * @return string
     */
    public function getBlogName()
    {
        return $this->blogName;
    }

    /**
     * Set blogIntro
     *
     * @param string $blogIntro
     *
     * @return Author
     */
    public function setBlogIntro($blogIntro)
    {
        $this->blogIntro = $blogIntro;

        return $this;
    }

    /**
     * Get blogIntro
     *
     * @return string
     */
    public function getBlogIntro()
    {
        return $this->blogIntro;
    }

    /**
     * Set blogHeadImg
     *
     * @param string $blogHeadImg
     *
     * @return Author
     */
    public function setBlogHeadImg($blogHeadImg)
    {
        $this->blogHeadImg = $blogHeadImg;

        return $this;
    }

    /**
     * Get blogHeadImg
     *
     * @return string
     */
    public function getBlogHeadImg()
    {
        return $this->blogHeadImg;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     *
     * @return Author
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

