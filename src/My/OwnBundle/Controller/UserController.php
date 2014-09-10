<?php

namespace My\OwnBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use My\OwnBundle\Entity\User;
use My\OwnBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
	
	/**
	* @Route("users", name="users")
	*	@Template()
	*/
	
	public function indexAction(){
  	$em = $this->getDoctrine()->getManager();

  	$users = $em->getRepository('MyOwnBundle:User')->findAll();
				
		return array("users" => $users);
	}
	
  /**
   *
	 * @Route("users/{id}/edit", name="user_edit")
   * @Method("GET")
   * @Template()
   */
  public function editAction($id)
  {
			$em = $this->getDoctrine()->getManager();
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $entity = $em->getRepository('MyOwnBundle:User')->find($id);

      if (!$entity) {
          throw $this->createNotFoundException('Unable to find User entity.');
      }
			
      $editForm = $this->createEditForm($entity);
			$deleteForm = $this->createDeleteForm($id);

      return array(
          'edit_form'   => $editForm->createView(),
					'delete_form'=> $deleteForm->createView(),
      );
  }
	
  /**
   * Edits an existing Category entity.
   *
   * @Route("users/{id}/update", name="user_update")
   * @Method("PUT")
   * @Template()
   */
  public function updateAction(Request $request, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $em = $this->getDoctrine()->getManager();
			
      $entity = $em->getRepository('MyOwnBundle:User')->find($id);

      if (!$entity) {
          throw $this->createNotFoundException('Unable to find User entity.');
      }
      $editForm = $this->createEditForm($entity);
      $editForm->handleRequest($request);

      if ($editForm->isValid()) {
          $em->flush();
          return $this->redirect($this->generateUrl('users', array('id' => $id)));
      }
			
			return $this->render('MyOwnBundle:User:edit.html.twig', array(
			        'edit_form' => $editForm->createView(),
			));
  }
	
  /**
   *
	 * @Route("users/{id}/delete", name="user_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, $id)
  {
			$user = $this->getUser();
			
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $form = $this->createDeleteForm($id);
      $form->handleRequest($request);
			
      if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
					
          $entity = $em->getRepository('MyOwnBundle:User')->find($id);

          if (!$entity) {
              throw $this->createNotFoundException('Unable to find User entity.');
          }
					
          $em->remove($entity);
          $em->flush();
      }

      return $this->redirect($this->generateUrl('users', array()));
  }
	
  private function createEditForm(User $entity)
  {
      $form = $this->createForm(new UserType(), $entity, array(
          'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
          'method' => 'PUT',
      ));

      $form->add('submit', 'submit', array('label' => 'Update'));

      return $form;
  }
	
  private function createDeleteForm($id)
  {
      return $this->createFormBuilder()
          ->setAction($this->generateUrl('user_delete', array('id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm()
      ;
  }
}
