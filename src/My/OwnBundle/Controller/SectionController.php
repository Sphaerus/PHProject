<?php

namespace My\OwnBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use My\OwnBundle\Entity\Section;
use My\OwnBundle\Form\SectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


class SectionController extends Controller
{
	/**
	* @Route("category/{category_id}/section/{id}", name="section_show")
	*	@Template()
	*/
	
	public function showAction($category_id, $id){
  	$em = $this->getDoctrine()->getManager();

  	$category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

  	if (!$category) {
      throw $this->createNotFoundException('Unable to find Category entity.');
  	}
		
    $sections = $category->getSections();
		$section = $this->findResource($sections, $id);
		
  	if (!$section) {
      throw $this->createNotFoundException('Unable to find Section entity.');
  	}
				
		return array("section" => $section);
	}
  /**
   * Displays a form to create a new Category entity.
	 * @Route("category/{category_id}/section_new", name="section_new")
   * @Template()
   */
  public function newAction($category_id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
			$em = $this->getDoctrine()->getManager();
      $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);
			
      if (!$category) {
          throw $this->createNotFoundException('Unable to find Category entity.');
      }
			
      $section = new Section();
			$section->setCategory($category);
      $form = $this->createCreateForm($section, $category_id);
			
      return array(
          'section' => $section,
          'form'   => $form->createView(),
      );
  }
	
  /**
   * Creates a new Section entity.
	 * @Route("/category/{category_id}/section_create", name="section_create")
   * @Method("POST")
   * @Template("MyOwnBundle:Section:new.html.twig")
   */
  public function createAction(Request $request, $category_id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $entity = new Section();
      $form = $this->createCreateForm($entity, $category_id);
      $form->handleRequest($request);

      if ($form->isValid()) {
					$em = $this->getDoctrine()->getManager();
	      	$category = $em->getRepository('MyOwnBundle:Category')->find($category_id);
			
	      	if (!$category) {
	          throw $this->createNotFoundException('Unable to find Category entity.');
	      	}
					
          $em = $this->getDoctrine()->getManager();
					$entity->setCategory($category);
          $em->persist($entity);
          $em->flush();

          return $this->redirect($this->generateUrl('category_show', array('id' => $category_id)));
      }
			
			return $this->render('MyOwnBundle:Topic:new.html.twig', array(
			        'form' => $form->createView(),
			    ));
  }
	
  /**
   * Displays a form to edit an existing Category entity.
   *
		 * @Route("category/{category_id}/section/{id}/edit", name="section_edit")
   * @Method("GET")
   * @Template()
   */
  public function editAction($category_id, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $em = $this->getDoctrine()->getManager();

      $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

      if (!$category) {
          throw $this->createNotFoundException('Unable to find Category entity.');
      }
			
			$section = $this->findResource($category->getSections(), $id);
			
      if (!$section) {
          throw $this->createNotFoundException('Unable to find Section entity.');
      }
			
      $editForm = $this->createEditForm($section);
			$deleteForm = $this->createDeleteForm($category_id, $id);

      return array(
          'section'      => $section,
          'edit_form'   => $editForm->createView(),
          'delete_form' => $deleteForm->createView(),
      );
  }
	
  /**
   *
		 * @Route("category/{category_id}/section/{id}/update", name="section_update")
   * @Method("PUT")
   * @Template("MyOwnBundle:Category:edit.html.twig")
   */
  public function updateAction(Request $request, $category_id, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $em = $this->getDoctrine()->getManager();

      $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

      if (!$category) {
          throw $this->createNotFoundException('Unable to find Category entity.');
      }
			
			$section = $this->findResource($category->getSections(), $id);
			
			if (!$section){
				throw $this->createNotFoundException('Unable to find Section entity.');
			}

      $editForm = $this->createEditForm($section);
      $editForm->handleRequest($request);

      if ($editForm->isValid()) {
          $em->flush();

          return $this->redirect($this->generateUrl('section', array('category_id'=> $category_id)));
      }
			
			$deleteForm = $this->createDeleteForm($category_id, $id);
			

			return $this->render('MyOwnBundle:Section:edit.html.twig', array(
			        'edit_form' => $editForm->createView(),
							'delete_form' => $deleteForm->createView(),
			    ));
  }
	
  /**
  * Creates a form to edit a Section entity.
  *
  * @param Category $entity The entity
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createEditForm(Section $section)
  {
      $form = $this->createForm(new SectionType(), $section, array(
          'action' => $this->generateUrl('section_update', array('category_id' => $section->getCategory()->getId(), 'id' => $section->getId())),
          'method' => 'PUT',
      ));

      $form->add('submit', 'submit', array('label' => 'Update'));

      return $form;
  }
	
  /**
   *
   * @Route("category/{category_id}/section/{id}/delete", name="section_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, $category_id, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
			if($user->getAdmin() == FALSE){			
				throw new HttpException(401, "You have no power here...");
			}
			
      $form = $this->createDeleteForm($category_id, $id);
      $form->handleRequest($request);

      if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

          if (!$category) {
              throw $this->createNotFoundException('Unable to find Category entity.');
          }
					
					$sections = $category->getSections();
					$section = $this->findResource($sections, $id);
					
          if (!$section) {
              throw $this->createNotFoundException('Unable to find Section entity.');
          }
					
					$em = $this->getDoctrine()->getManager();
          $em->remove($section);
          $em->flush();
      }

      return $this->redirect($this->generateUrl('category_show', array('id'=> $category_id)));
  }
	
  /**
   * Creates a form to create a Section entity.
   *
   * @param Section $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createCreateForm(Section $entity, $category_id)
  {
      $form = $this->createForm(new SectionType(), $entity, array(
          'action' => $this->generateUrl('section_create', array('category_id' => $category_id)),
          'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Create'));

      return $form;
  }
	
  /**
   * @param mixed $id The entity id
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm($category_id, $id)
  {
      return $this->createFormBuilder()
          ->setAction($this->generateUrl('section_delete', array('category_id'=>$category_id, 'id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm()
      ;
  }
	
	private function findResource($sections, $id){
		foreach($sections as $section){
			if($section->getId() == $id){
				return $section;
			}
		}
		return null;
	}
}
