<?php

namespace My\OwnBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;


/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
		 
 		/**
 		* @ORM\OneToMany(targetEntity="Post", mappedBy="user")
 		*/ 
		 
 		protected $posts; 
		
 		/**
 		* @ORM\OneToMany(targetEntity="Topic", mappedBy="user")
 		*/ 
		 
 		protected $topics; 
		
		/**
		     * @var boolean $active
		     * @ORM\Column(name="admin", type="boolean")
		     */
			 
    protected $admin=FALSE;


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
     * Add posts
     *
     * @param \My\OwnBundle\Entity\Post $posts
     * @return User
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

    /**
     * Add topics
     *
     * @param \My\OwnBundle\Entity\Topic $topics
     * @return User
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
    

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}
