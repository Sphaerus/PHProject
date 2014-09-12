<?php

namespace My\OwnBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
		 * @Assert\NotBlank(message = "content of a post cannot be blank" )
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;
		 
  	/**
  	*@ORM\ManyToOne(targetEntity="Topic", inversedBy="posts")
  	* @ORM\JoinColumn(name="topic_id", onDelete="CASCADE")
  	*/ 
		
  	protected $topic;
		
  	/**
  	*@ORM\ManyToOne(targetEntity="User", inversedBy="posts")
  	* @ORM\JoinColumn(name="user_id", onDelete="CASCADE")
	  * Assert\Blank()		
  	*/ 
		
  	protected $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
     * Set title
     *
     * @param string $title
     * @return Post
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
     * Set section
     *
     * @param \My\OwnBundle\Entity\Topic $section
     * @return Post
     */
    public function setSection(\My\OwnBundle\Entity\Topic $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \My\OwnBundle\Entity\Topic 
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set topic
     *
     * @param \My\OwnBundle\Entity\Topic $topic
     * @return Post
     */
    public function setTopic(\My\OwnBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \My\OwnBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set user
     *
     * @param \My\OwnBundle\Entity\User $user
     * @return Post
     */
    public function setUser(\My\OwnBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \My\OwnBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
