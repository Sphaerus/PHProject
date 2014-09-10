<?php

namespace My\OwnBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use My\OwnBundle\Entity\Topic;
use My\OwnBundle\Form\TopicType;
use My\OwnBundle\Entity\Post;
use My\OwnBundle\Form\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


class PostController extends Controller
{
	
  /**
	 * @Route("category/{category_id}/section/{section_id}/topic/{topic_id}/post/new", name="post_new")
   * @Template()
   */
  public function newAction($category_id, $section_id, $topic_id)
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
			
      $topic = $this->findResource($section->getTopics(), $topic_id);
			
      if (!$topic) {
          throw $this->createNotFoundException('Unable to find Topic entity.');
      }
			
			$post = new Post();
      $form = $this->createCreateForm($post, $category_id, $section_id, $topic_id);
			
      return array(
				'section' => $section,
          'topic' => $topic,
					'post' => $post,
          'form'   => $form->createView(),
      );
  }
	
  /**
	 * @Route("/category/{category_id}/section/{section_id}/topic/{topic_id}/post/create", name="post_create")
   * @Method("POST")
   */
  public function createAction(Request $request, $category_id, $section_id, $topic_id)
  {
			$user = $this->getUser();
			if($user == ""){			
				return $this->redirect($this->generateUrl('fos_user_security_login'));
			}
      $post = new Post();
      $form = $this->createCreateForm($post, $category_id, $section_id, $topic_id);
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
					
					$topic = $this->findResource($section->getTopics(), $topic_id);
					
	      	if (!$topic) {
	          throw $this->createNotFoundException('Unable to find Topic entity.');
	      	}
					
          $em = $this->getDoctrine()->getManager();
					$post->setTopic($topic);
					$post->setUser($user);
          $em->persist($post);
          $em->flush();

          return $this->redirect($this->generateUrl('topic_show', array('category_id' => $category_id, 'section_id'=> $section->getId(), 'id'=> $topic->getId())));
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
			
			$topic = $this->findResource($section->getTopics(), $topic_id);

			return $this->render('MyOwnBundle:Post:new.html.twig', array(
			        'form' => $form->createView(),
							'topic' => $topic,
			    ));
  }
	
  /**
   * Displays a form to edit an existing Category entity.
   *
		 * @Route("category/{category_id}/section/{section_id}/topic/{topic_id}/post/{id}/edit", name="post_edit")
   * @Method("GET")
   * @Template()
   */
  public function editAction($category_id, $section_id, $topic_id, $id)
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
			
			$topic = $this->findResource($section->getTopics(), $topic_id);
			
      if (!$topic) {
          throw $this->createNotFoundException('Unable to find Topic entity.');
      }
			
			$post = $this->findResource($topic->getPosts(), $id);
			
			if(!$post){
					throw $this->createNotFoundException('Unable to find Post entity.');
			}
			
			if($user->getAdmin() == FALSE){
				if ($post->getUser()->getId() != $user->getId()){
					throw new HttpException(401, "You are trying to edit post that is not yours. It's not nice really, why are you doing this? Get your own post...");
				}
			}	
			
      $editForm = $this->createEditForm($post, $category_id, $section_id, $topic_id);
			#$deleteForm = $this->createDeleteForm($category_id, $section_id, $topic_id, $post_id);
			

      return array(
          'section'     => $section,
					'post' 				=> $post,
          'edit_form'   => $editForm->createView(),
          #'delete_form' => $deleteForm->createView(),
      );
  }
	
  /**
   *
		 * @Route("category/{category_id}/section/{section_id}/topic/{topic_id}/post/{id}/update", name="post_update")
   * @Method("PUT")
   * @Template("MyOwnBundle:Category:edit.html.twig")
   */
  public function updateAction(Request $request, $category_id, $section_id, $topic_id, $id)
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
			
			$topic = $this->findResource($section->getTopics(), $topic_id);

			if (!$topic){
				throw $this->createNotFoundException('Unable to find Topic entity.');
			}
			
			$post = $this->findResource($topic->getPosts(), $id);
			
			if (!$post){
				throw $this->createNotFoundException('Unable to find Post entity.');
			}
			if($user->getAdmin() == FALSE){
				if ($post->getUser()->getId() != $user->getId()){
					throw new HttpException(401, "You are trying to edit post that is not yours. It's not nice really, why are you doing this? Get your own post...");
				}
			}	
			
      $editForm = $this->createEditForm($post, $category_id, $section_id, $topic_id);
      $editForm->handleRequest($request);
			
      if ($editForm->isValid()) {
          $em->flush();
          return $this->redirect($this->generateUrl('topic_show', array('category_id'=> $category_id, 'section_id'=> $section_id, 'id'=> $topic_id)));
      }
			
			return $this->render('MyOwnBundle:Post:edit.html.twig', array(
			        'edit_form' => $editForm->createView(),
			    ));
      #return $this->redirect($this->generateUrl('post_edit', array('category_id'=> $category_id, 'section_id'=> $section_id, 'topic_id'=> $topic_id, 'id'=>$id)));
      
  }
	
  private function createCreateForm(Post $post, $category_id, $section_id, $topic_id)
  {
      $form = $this->createForm(new PostType(), $post, array(
          'action' => $this->generateUrl('post_create', array('category_id' => $category_id, 'section_id' => $section_id, 'topic_id'=> $topic_id)),
          'method' => 'POST',
      ));

      $form->add('submit', 'submit', array('label' => 'Create'));

      return $form;
  }

  private function createEditForm(Post $post, $category_id, $section_id, $topic_id)
  {
      $form = $this->createForm(new PostType(), $post, array(
          'action' => $this->generateUrl('post_update', array('category_id' => $category_id, 'section_id' => $section_id, 'topic_id'=> $topic_id, 'id' => $post->getId() )),
          'method' => 'PUT',
      ));

      $form->add('submit', 'submit', array('label' => 'Update'));

      return $form;
  }
	
  /**
   *
	 * @Route("category/{category_id}/section/{section_id}/topic/{topic_id}/post/{id}/delete", name="post_delete")
   * @Method("DELETE")
   */
  public function deleteAction($category_id, $section_id, $topic_id, $id)
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
					
					$topic = $this->findResource($section->getTopics(), $topic_id);
					
          if (!$topic) {
              throw $this->createNotFoundException('Unable to find Topic entity.');
          }
					
					$post = $this->findResource($topic->getPosts(), $id);
					
          if (!$post) {
              throw $this->createNotFoundException('Unable to find Post entity.');
          }
					
					if($user->getAdmin() == FALSE){
					
						if ($post->getUser()->getId() != $user->getId()){
							throw new HttpException(401, "You are trying to delete post that is not yours. It's not nice really, why are you doing this? Get your own post...");
						}
					}	
					
          $em->remove($post);
          $em->flush();

      return $this->redirect($this->generateUrl('topic_show', array('category_id'=> $category_id, 'section_id' => $section_id, 'id'=> $topic_id)));
  }
	
	private function findResource($sections, $id){
		foreach($sections as $section){
			if($section->getId() == $id){
				return $section;
			}
		}
		return null;
	}
	
	private function performAuthentication(){
		$user = $this->getUser();
		#throw $this->createNotFoundException($user == "");
		if($user == ""){
      #return $this->redirect($this->generateUrl('main_page');
			
			#return $this->redirect($this->generateUrl('fos_user_security_login'));
			#throw new HttpException(401, "Whoops! Looks like the post you are looking for dosen't exist. :/");
		}
	}
}
