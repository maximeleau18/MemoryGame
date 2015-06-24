<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use MG\MemoryGameBundle\Entity\HumanPlayer;
use MG\MemoryGameBundle\Form\RegistrationType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use MG\MemoryGameBundle\Form\ProfileType;
use Symfony\Component\Form\FormError;

class UserController extends Controller
{
	/**
	 * @var ContainerInterface
	 */
	protected $container;
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $objUser = $this->get('security.context')->getToken()->getUser();
        
        if (!$objUser){
        	// If user is still connected
        	return $this->render('MGMemoryGameBundle:Default:index.html.twig');
        }else{
        	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
        		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        	} else {
        		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
        	}
        	
        	$form = $this->createForm(new RegistrationType(), new HumanPlayer());
        	        	
        	return $this->render('MGMemoryGameBundle:User:connexion.html.twig', array(
        			// last username entered by the user
        			'last_login' => $session->get(SecurityContext::LAST_USERNAME),
        			'errorLogin'         => $error,
        			'errorRegister'         => '',
        			'form' => $form->createView(),
        			'success' => '',
        		 	));
        }
    }
    
    public function createAction()
    {
    	$request = $this->getRequest();
    	$form = $this->createForm(new RegistrationType(), new HumanPlayer());
    	$form->bind($request);
    	
    	if ($form->isValid()){
    		$user= new HumanPlayer();
    		$factory = $this->get('security.encoder_factory');
    		
    		$encoder = $factory->getEncoder($user);    		
    		$passwordEncoded = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
    		$user->setPassword($passwordEncoded);
    		$user->setUsername($form->get('username')->getData());
    		$user->setBirthdate($form->get('birthdate')->getData());
    		$user->setCreatedAt(New \DateTime());
    		
    		$em = $this->getDoctrine()->getEntityManager();
    		$em->persist($user);
    		$em->flush();
    		// Reset values in the form
    		$form = $this->createForm(new RegistrationType(), new HumanPlayer());
    		
    		return $this->render('MGMemoryGameBundle:User:connexion.html.twig', array(
        		'last_login' => '',
        		'errorLogin'         => '',
    			'errorRegister'         => '',
    			'form' => $form->createView(),
    			'success' => 'Votre compte a bien été enregistré. Vous pouvez vous connecter.',
    	));
    	}
    	
    	$error = $form->getErrors();
    	return $this->render('MGMemoryGameBundle:User:connexion.html.twig', array(
        		'last_login' => '',
        		'errorLogin'         => '',
    			'errorRegister'         => $error,
    			'form' => $form->createView(),
    			'success' => '',
    	));
    	
    }
    
    public function editAction()
    {    	
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		$userId = $this->get('security.context')->getToken()->getUser()->getId();
    		$em = $this->getDoctrine()->getEntityManager();
			$user = $em->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
			
			if (!$user){
				throw $this->createNotFoundException('Impossible de trouver ce joueur.');
			}
			
    		$editForm = $this->createForm(new ProfileType(), $user);    		
    		$deleteForm = $this->createDeleteForm($userId);
    		
    		return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
    				'user'      => $user,
    				'edit_form'   => $editForm->createView(),
    				'delete_form' => $deleteForm->createView(),
    		));    		
    	}else{
    		return $this->redirect($this->generateUrl('mg_memory_game_homepage'));    		
    	}   	
    }
    
    public function updateAction()
    {
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
	    	$request = $this->getRequest();
	    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
	    	$em = $this->getDoctrine()->getEntityManager();
			$user = $em->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
	    
	    	if (!$user) {
	    		throw $this->createNotFoundException('Impossible de trouver ce joueur.');
	    	}
	    
	    	$editForm   = $this->createForm(new ProfileType(), $user);
	    	$deleteForm = $this->createDeleteForm($userId);
	    
	    	// Get user password in DataBase
	    	$oldPassword = $user->getPassword();
	    	
	    	$editForm->bind($request);
	    
	    	if ($editForm->isValid()) {
	    		$factory = $this->get('security.encoder_factory');	    		
	    		$encoder = $factory->getEncoder($user);
	    		// Get the current password
	    		$currentPassword = $editForm->get('currentpassword')->getData();	   	    		
	    		$currentPasswordEncoded = $encoder->encodePassword($currentPassword, $user->getSalt());
	    		
	    		if ($currentPasswordEncoded === $oldPassword){	    			
					// Update user in DataBase
	    			$newPasswordEncoded = $encoder->encodePassword($editForm->get('password')->getData(), $user->getSalt());
	    			if ($oldPassword === $newPasswordEncoded){
	    				$editForm->get('currentpassword')->addError(new FormError('Votre nouveau mot de passe doit être différent de l\'ancien.'));
	    				
	    				return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    						'user'      => $user,
	    						'edit_form'   => $editForm->createView(),
	    						'delete_form' => $deleteForm->createView(),
	    				
	    				));
	    			}else{	    			
		    			$user->setPassword($newPasswordEncoded);
		    			$em->persist($user);
		    			$em->flush();
		    			
		    			return $this->redirect($this->generateUrl('mg_memory_game_homepage'));
	    			}
	    		}else {
	    			$editForm->get('currentpassword')->addError(new FormError('Votre ancien mot de passe n\'est pas valide.'));
	    			
	    			return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    					'user'      => $user,
	    					'edit_form'   => $editForm->createView(),
	    					'delete_form' => $deleteForm->createView(),
	    					
	    			));
	    		}    		
	    	}
	    
	    	return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    			'user'      => $user,
	    			'edit_form'   => $editForm->createView(),
	    			'delete_form' => $deleteForm->createView(),
	    	));
	    }else{
	    	return $this->redirect($this->generateUrl('mg_memory_game_homepage'));
	    }
    }
        
    public function deleteAction()
    {
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){	
	    	$request = $this->getRequest();
	    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
	    	$form = $this->createDeleteForm($userId);
	    	$form->bind($request);
	    
	    	if ($form->isValid()) {
	    		$em = $this->getDoctrine()->getEntityManager();
	    		$user = $em->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
	    
	    		if (!$user) {
	    			throw $this->createNotFoundException('Impossible de trouver ce joueur.');
	    		}
	    
	    		$em->remove($user);
	    		$em->flush();
	    	}
	    
	    	return $this->redirect($this->generateUrl('mg_memory_game_homepage'));
	    }else{
	    	return $this->redirect($this->generateUrl('mg_memory_game_homepage'));
	    }
    }
    
    private function createDeleteForm($userId)
    {
    	return $this->createFormBuilder(array('user_id' => $userId))
    	->add('user_id', 'hidden')
    	->getForm()
    	;
    }
}