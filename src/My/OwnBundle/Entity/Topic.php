<?php

namespace My\OwnBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Topic
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Topic
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
     * @ORM\Column(name="name", type="string", length=255)
		 * @Assert\NotBlank(message = "name of a topic cannot be blank" )	 
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
		 * @Assert\NotBlank(message = "content of a topic cannot be blank" )	 
     */
    private $content;
		 
 		/**
 		*@ORM\ManyToOne(targetEntity="Section", inversedBy="topics")
 		* @ORM\JoinColumn(name="section_id", onDelete="CASCADE")
 		*/ 
		
 		protected $section;	
		
		/**
		* @ORM\OneToMany(targetEntity="Post", mappedBy="topic")
		*/ 
		 
		protected $posts; 
		
  	/**
  	*@ORM\ManyToOne(targetEntity="User", inversedBy="topics")
  	* @ORM\JoinColumn(name="user_id", onDelete="CASCADE")
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
     * Set name
     *
     * @param string $name
     * @return Topic
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Topic
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
     * Set section
     *
     * @param \My\OwnBundle\Entity\Section $section
     * @return Topic
     */
    public function setSection(\My\OwnBundle\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \My\OwnBundle\Entity\Section 
     */
    public function getSection()
    {
        return $this->section;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add posts
     *
     * @param \My\OwnBundle\Entity\Post $posts
     * @return Topic
     */
    public function addPost(\My\OwnBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \My\OwnBundle\Entity\Post $posts
     */
    public function removePost(\My\OwnBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
		
		public function __toString()
		{
		    return $this->name;
		}

    /**
     * Set user
     *
     * @param \My\OwnBundle\Entity\User $user
     * @return Topic
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
