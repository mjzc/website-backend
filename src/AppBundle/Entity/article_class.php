<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * article_class
 *
 * @ORM\Table(name="article_class")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\article_classRepository")
 */
class article_class implements \JsonSerializable
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
     * @ORM\Column(name="class_name", type="string", length=255)
     */
    private $className;

    function __construct($id, $className) {
        $this->id = $id;
        $this->name = $className;
    }

    function jsonSerialize() {
        return array(
            'id' => $this->id,
            'name' => $this->className,
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
     * Set className
     *
     * @param string $className
     *
     * @return article_class
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}

