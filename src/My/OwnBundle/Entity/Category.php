<?php

namespace My\OwnBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="My\OwnBundle\Entity\CategoryRepository")
 */
class Category
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
		 * @Assert\NotBlank(message = "name of a category cannot be blank" )	 
     */
    private $name;
		 
		/**
		* @ORM\OneToMany(targetEntity="Section", mappedBy="category")
		*/ 
		 
		protected $sections; 


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
     * @return Category
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
     * Set sections
     *
     * @param \My\OwnBundle\Entity\Section $sections
     * @return Category
     */
    public function setSections(\My\OwnBundle\Entity\Section $sections = null)
    {
        $this->sections = $sections;

        return $this;
    }

    /**
     * Get sections
     *
     * @return \My\OwnBundle\Entity\Section 
     */
    public function getSections()
    {
        return $this->sections;
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
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sections
     *
     * @param \My\OwnBundle\Entity\Section $sections
     * @return Category
     */
    public function addSection(\My\OwnBundle\Entity\Section $sections)
    {
        $this->sections[] = $sections;

        return $this;
    }

    /**
     * Remove sections
     *
     * @param \My\OwnBundle\Entity\Section $sections
     */
    public function removeSection(\My\OwnBundle\Entity\Section $sections)
    {
        $this->sections->removeElement($sections);
    }
}
