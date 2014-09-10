<?php

namespace My\OwnBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use My\OwnBundle\Entity\Category;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main_page")
     * @Template()
     */
    public function indexAction()
    {
			$this->setUser();
			$em = $this->getDoctrine()->getEntityManager();
				
			$categories = $em->getRepository('MyOwnBundle:Category')->FindAll();	
      return array("categories" => $categories);    
    }
		
		private function setUser(){
			$user = $this->getUser();
		}
}
