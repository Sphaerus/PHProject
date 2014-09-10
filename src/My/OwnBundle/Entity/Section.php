<?php

namespace My\OwnBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Section
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Section
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
		 * @Assert\NotBlank(message = "name of a section cannot be blank" )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
		 * @Assert\NotBlank(message = "description of a section cannot be blank" )	 
     */
    private $description;
		 
		/**
		*@ORM\ManyToOne(targetEntity="Category", inversedBy="sections")
		* @ORM\JoinColumn(name="category_id", onDelete="CASCADE")
		*/ 
		
		protected $category;	
		
		/**
		* @ORM\OneToMany(targetEntity="Topic", mappedBy="section")
		*/ 
		 
		protected $topics; 

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
     * @return Section
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
     * Set description
     *
     * @param string $description
     * @return Section
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \My\OwnBundle\Entity\Category $category
     * @return Section
     */
    public function setCategory(\My\OwnBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \My\OwnBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
		
		public function __toString()
		{
		    return $this->name;
		}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add topics
     *
     * @param \My\OwnBundle\Entity\Topic $topics
     * @return Section
     */
    public function addTopic(\My\OwnBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;

        return $this;
    }

    /**
     * Remove topics
     *
     * @param \My\OwnBundle\Entity\Topic $topics
     */
    public function removeTopic(\My\OwnBundle\Entity\Topic $topics)
    {
        $this->topics->removeElement($topics);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
    }
}
