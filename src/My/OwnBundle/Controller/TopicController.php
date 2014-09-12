<?php

namespace My\OwnBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use My\OwnBundle\Entity\Topic;
use My\OwnBundle\Form\TopicType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


class TopicController extends Controller
{
	
	/**
	* @Route("category/{category_id}/section/{section_id}/topic/{id}", name="topic_show")
	*	@Template()
	*/
	
	public function showAction($category_id, $section_id, $id){
  	$em = $this->getDoctrine()->getManager();

  	$category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

  	if (!$category) {
      throw $this->createNotFoundException('Unable to find Category entity.');
  	}
		
    $sections = $category->getSections();
		$section = $this->findResource($sections, $section_id);
		
  	if (!$section) {
      throw $this->createNotFoundException('Unable to find Section entity.');
  	}
		
		$topics = $section->getTopics();
		
		$topic = $this->findResource($topics, $id);
		
  	if (!$topic) {
      throw $this->createNotFoundException('Unable to find Topic entity.');
  	}
		
		return array("section" => $section, 'topic' => $topic);
	}
	
  /**
   * Displays a form to create a new Category entity.
	 * @Route("category/{category_id}/section/{section_id}/topic_new", name="topic_new")
   * @Template()
   */
  public function newAction($category_id, $section_id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			$em = $this->getDoctrine()->getManager();
      $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);
			
      if (!$category) {
          throw $this->createNotFoundException('Unable to find Category entity.');
      }
			
			$sections = $category->getSections();
			
			$section = $this->findResource($sections, $section_id);
			
      if (!$section) {
          throw $this->createNotFoundException('Unable to find Section entity.');
      }
			
      $topic = new Topic();
      $form = $this->createCreateForm($topic, $category_id, $section_id);
			
      return array(
          'section' => $section,
          'form'   => $form->createView(),
      );
  }
	
  /**
	 * @Route("/category/{category_id}/section/{section_id}/topic_create", name="topic_create")
   * @Method("POST")
   * @Template("MyOwnBundle:Section:new.html.twig")
   */
  public function createAction(Request $request, $category_id, $section_id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
      $topic = new Topic();
      $form = $this->createCreateForm($topic, $category_id, $section_id);
      $form->handleRequest($request);

      if ($form->isValid()) {
					$em = $this->getDoctrine()->getManager();
	      	$category = $em->getRepository('MyOwnBundle:Category')->find($category_id);
			
	      	if (!$category) {
	          throw $this->createNotFoundException('Unable to find Category entity.');
	      	}
					
					$section = $this->findResource($category->getSections(), $section_id);
					
	      	if (!$section) {
	          throw $this->createNotFoundException('Unable to find Section entity.');
	      	}
					
          $em = $this->getDoctrine()->getManager();
					$topic->setSection($section);
					$topic->setUser($user);
          $em->persist($topic);
          $em->flush();

          return $this->redirect($this->generateUrl('topic_show', array('category_id' => $category_id, 'section_id'=> $section->getId(), 'id'=> $topic->getId())));
      }

			return $this->render('MyOwnBundle:Topic:new.html.twig', array(
			        'form' => $form->createView(),
			    ));
  }
	
  /**
   * Displays a form to edit an existing Category entity.
   *
		 * @Route("category/{category_id}/section/{section_id}/topic/{id}/edit", name="topic_edit")
   * @Method("GET")
   * @Template()
   */
  public function editAction($category_id, $section_id, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
      $em = $this->getDoctrine()->getManager();

      $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

      if (!$category) {
          throw $this->createNotFoundException('Unable to find Category entity.');
      }
			
			$section = $this->findResource($category->getSections(), $section_id);
			
      if (!$section) {
          throw $this->createNotFoundException('Unable to find Section entity.');
      }
			
			$topic = $this->findResource($section->getTopics(), $id);
			
      if (!$topic) {
          throw $this->createNotFoundException('Unable to find Topic entity.');
      }
			
			if($user->getAdmin() == FALSE){
				if($user->getId() != $topic->getUser()->getId()){
					throw new HttpException(401, "You are trying to edit topic that is not yours. It's not nice really, why are you doing this? Geto your own post...");
				}
			}	
			
      $editForm = $this->createEditForm($topic, $category_id, $section_id);
			$deleteForm = $this->createDeleteForm($category_id, $section_id, $id);
			

      return array(
          'section'      => $section,
          'edit_form'   => $editForm->createView(),
          'delete_form' => $deleteForm->createView(),
      );
  }
	
  /**
   *
		 * @Route("category/{category_id}/section/{section_id}/topic/{id}/update", name="topic_update")
   * @Method("PUT")
   * @Template("MyOwnBundle:Category:edit.html.twig")
   */
  public function updateAction(Request $request, $category_id, $section_id, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			
      $em = $this->getDoctrine()->getManager();

      $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

      if (!$category) {
          throw $this->createNotFoundException('Unable to find Category entity.');
      }
			
			$section = $this->findResource($category->getSections(), $section_id);
			
			if (!$section){
				throw $this->createNotFoundException('Unable to find Section entity.');
			}
			
			$topic = $this->findResource($section->getTopics(), $id);

			if (!$topic){
				throw $this->createNotFoundException('Unable to find Topic entity.');
			}
			
			if($user->getAdmin() == FALSE){
				if($user->getId() != $topic->getUser()->getId()){
					throw new HttpException(401, "You are trying to edit topic that is not yours. It's not nice really, why are you doing this? Geto your own topic...");
				}
			}	
			
      $editForm = $this->createEditForm($topic, $category_id, $section_id);
      $editForm->handleRequest($request);

      if ($editForm->isValid()) {
          $em->flush();

          return $this->redirect($this->generateUrl('topic_show', array('category_id'=> $category_id, 'section_id'=> $section_id, 'id'=> $id)));
      }
			$deleteForm = $this->createDeleteForm($category_id, $section_id, $id);
			
			return $this->render('MyOwnBundle:Topic:edit.html.twig', array(
			        'edit_form' => $editForm->createView(),
							'delete_form' => $deleteForm->createView(),
			    ));
  }
	
  /**
   *
		 * @Route("category/{category_id}/section/{section_id}/topic/{id}/delete", name="topic_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, $category_id, $section_id, $id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
			$form = $this->createDeleteForm($category_id, $section_id, $id);
      $form->handleRequest($request);
			
      if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
					
          $category = $em->getRepository('MyOwnBundle:Category')->find($category_id);

          if (!$category) {
              throw $this->createNotFoundException('Unable to find Category entity.');
          }
					
					$section = $this->findResource($category->getSections(), $section_id);
					
          if (!$section) {
              throw $this->createNotFoundException('Unable to find Section entity.');
          }
					
					$topic = $this->findResource($section->getTopics(), $id);
					
          if (!$topic) {
              throw $this->createNotFoundException('Unable to find Topic entity.');
          }
					
					if($user->getAdmin() == FALSE){
						if($user->getId() != $topic->getUser()->getId() ){
							throw new HttpException(401, "You are trying to delete topic that is not yours. It's not nice really, why are you doing this? Get your own topic...");
						}
					}	
					
          $em->remove($topic);
          $em->flush();
      }

      return $this->redirect($this->generateUrl('section_show', array('category_id'=> $category_id, 'id' => $section_id)));
  }
	
  /**
   * Creates a form to create a Topic entity.
   *
   * @param Section $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createCreateForm(Topic $topic, $category_id, $section_id)
  {
      $form = $this->createForm(new TopicType(), $topic, array(
          'action' => $this->generateUrl('topic_create', array('category_id' => $category_id, 'section_id' => $section_id)),
          'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Create'));

      return $form;
  }
	
  /**
  * Creates a form to edit a Section entity.
  *
  * @param Category $entity The entity
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createEditForm(Topic $topic, $category_id, $section_id)
  {
      $form = $this->createForm(new TopicType(), $topic, array(
          'action' => $this->generateUrl('topic_update', array('category_id' => $category_id, 'section_id' => $section_id, 'id'=>$topic->getId() )),
          'method' => 'PUT',
      ));

      $form->add('submit', 'submit', array('label' => 'Update'));

      return $form;
  }
	
  /**
   * @param mixed $id The entity id
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm($category_id, $section_id, $id)
  {
      return $this->createFormBuilder()
          ->setAction($this->generateUrl('topic_delete', array('category_id'=>$category_id, 'section_id' => $section_id, 'id'=>$id)))
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
?>