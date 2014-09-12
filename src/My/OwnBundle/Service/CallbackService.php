<?php
	namespace My\OwnBundle\Service;
	
	use FOS\UserBundle\FOSUserEvents;
	use FOS\UserBundle\Event\FormEvent;
	use FOS\UserBundle\Event\FilterUserResponseEvent;
	use FOS\UserBundle\Event\GetResponseUserEvent;
	use FOS\UserBundle\Model\UserInterface;
	use Symfony\Component\DependencyInjection\ContainerAware;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CallbackService extends ContainerAware{

   public function customFunction(){
		 $user = $this->container->get('security.context')->getToken()->getUser();
   }

}