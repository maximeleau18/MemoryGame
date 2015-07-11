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
	
	protected $modes;
	
	protected $difficulties;
	
	protected $manager;
	
	public function loadData()
	{
		$this->manager = $this->getDoctrine()->getManager();
		$this->modes = $this->manager->getRepository('MGMemoryGameBundle:Mode')->findAll();
		$this->difficulties = $this->manager->getRepository('MGMemoryGameBundle:Difficulty')->findAll();
			
		if (!$this->modes) {
			throw $this->createNotFoundException('Aucun mode de jeu trouvé.');
		}
		if (!$this->difficulties) {
			throw $this->createNotFoundException('Aucune difficulté de jeu trouvée.');
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
    public function loginAction()
    {
    	$this->loadData();
        $request = $this->getRequest();
        $session = $request->getSession();
        $objUser = $this->get('security.context')->getToken()->getUser();
        
        if (!$objUser){
        	// If user is still connected        	        	 
        	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
        			'modes'      => $this->modes,
        			'difficulties' => $this->difficulties,
        	));
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
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
        		 	));
        }
    }
    
    public function createAction()
    {
    	$this->loadData();
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
    		
    		$this->manager->persist($user);
    		$this->manager->flush();
    		// Reset values in the form
    		$form = $this->createForm(new RegistrationType(), new HumanPlayer());
    		    		
    		return $this->render('MGMemoryGameBundle:User:connexion.html.twig', array(
        		'last_login' => '',
        		'errorLogin'         => '',
    			'errorRegister'         => '',
    			'form' => $form->createView(),
    			'success' => 'Votre compte a bien été enregistré. Vous pouvez vous connecter.',
	    		'modes'      => $this->modes,
	    		'difficulties' => $this->difficulties,
    	));
    	}
    	
    	$error = $form->getErrors();
    	return $this->render('MGMemoryGameBundle:User:connexion.html.twig', array(
        		'last_login' => '',
        		'errorLogin'         => '',
    			'errorRegister'         => $error,
    			'form' => $form->createView(),
    			'success' => '',
	    		'modes'      => $this->modes,
	    		'difficulties' => $this->difficulties,
    	));
    	
    }
    
    public function editAction()
    {    	
    	$this->loadData();
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		$userId = $this->get('security.context')->getToken()->getUser()->getId();
			$user = $this->manager->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
			
			if (!$user){
				throw $this->createNotFoundException('Impossible de trouver ce joueur.');
			}
			
    		$editForm = $this->createForm(new ProfileType(), $user);    		
    		$deleteForm = $this->createDeleteForm($userId);
    		
    		return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
    				'user'      => $user,
    				'edit_form'   => $editForm->createView(),
    				'delete_form' => $deleteForm->createView(),
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
    		));    		
    	}else{
    		$flashBag = $this->get('session')->getFlashBag();
    		$flashBag->get('notice');
    		$flashBag->set('notice', "Vous devez être connecté pour consulter cette page.");
	    	
	    	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
	    	));  		
    	}   	
    }
    
    public function updateAction()
    {
    	$this->loadData();
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
	    	$request = $this->getRequest();
	    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
			$user = $this->manager->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
	    
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
	    		$currentPassword = $editForm->get('currentpassword')->getData();	   	    		
	    		$currentPasswordEncoded = $encoder->encodePassword($currentPassword, $user->getSalt());
	    		
	    		if ($currentPasswordEncoded === $oldPassword){	    
	    			$newPasswordEncoded = $encoder->encodePassword($editForm->get('password')->getData(), $user->getSalt());
	    			if ($oldPassword === $newPasswordEncoded){
	    				$editForm->get('currentpassword')->addError(new FormError('Votre nouveau mot de passe doit être différent de l\'ancien.'));
	    				
	    				return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    						'user'      => $user,
	    						'edit_form'   => $editForm->createView(),
	    						'delete_form' => $deleteForm->createView(),
	    						'modes'      => $this->modes,
	    						'difficulties' => $this->difficulties,
	    				
	    				));
	    			}else{	    	
	    				// Update user in DataBase		
		    			$user->setPassword($newPasswordEncoded);
		    			$this->manager->persist($user);
		    			$this->manager->flush();
		    			
		    			$flashBag = $this->get('session')->getFlashBag();
		    			$flashBag->get('notice'); // gets message and clears type
		    			$flashBag->set('notice', "Votre compte a bien été modifié.");
		    			
		    			return $this->redirect($this->generateUrl('user_edit'));
	    			}
	    		}else {
	    			$editForm->get('currentpassword')->addError(new FormError('Votre ancien mot de passe n\'est pas valide.'));
	    			
	    			return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    					'user'      => $user,
	    					'edit_form'   => $editForm->createView(),
	    					'delete_form' => $deleteForm->createView(),
	    					'modes'      => $this->modes,
	    					'difficulties' => $this->difficulties,
	    					
	    			));
	    		}    		
	    	}
	    
	    	return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    			'user'      => $user,
	    			'edit_form'   => $editForm->createView(),
	    			'delete_form' => $deleteForm->createView(),
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
	    	));
	    }else{
    		$flashBag = $this->get('session')->getFlashBag();
    		$flashBag->get('notice');
    		$flashBag->set('notice', "Vous devez être connecté pour consulter cette page.");
	    	
	    	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
	    	));
	    }
    }
        
    public function deleteAction()
    {
    	$this->loadData();
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){	
	    	$request = $this->getRequest();
	    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
	    	$user = $this->manager->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
	    	
	    	if (!$user) {
	    		throw $this->createNotFoundException('Impossible de trouver ce joueur.');
	    	}
	    	
	    	$editForm   = $this->createForm(new ProfileType(), $user);
	    	$deleteForm = $this->createDeleteForm($userId);
	    	// Get user password in DataBase
	    	$oldPassword = $user->getPassword();
	    	
	    	$deleteForm->bind($request);
	    
	    	if ($deleteForm->isValid()) {	    	 		
	    		$factory = $this->get('security.encoder_factory');
	    		$encoder = $factory->getEncoder($user);
	    		$currentPassword = $deleteForm->get('currentpassword')->getData();
	    		$currentPasswordEncoded = $encoder->encodePassword($currentPassword, $user->getSalt());
	    		
	    		if($currentPasswordEncoded === $oldPassword){
	    			$this->manager->remove($user);
	    			$this->manager->flush();

	    			$this->get('security.context')->setToken(null);

	    			$flashBag = $this->get('session')->getFlashBag();
	    			$flashBag->get('notice'); // gets message and clears type
	    			$flashBag->set('notice', "Votre compte a bien été supprimé.");
	    			 
	    			return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
	    					'modes'      => $this->modes,
	    					'difficulties' => $this->difficulties,
	    			));
	    		}else{
	    			$deleteForm->get('currentpassword')->addError(new FormError('Votre ancien mot de passe n\'est pas valide.'));
	    		}			
	    	}
	    	
	    	return $this->render('MGMemoryGameBundle:User:edit.html.twig', array(
	    			'user'      => $user,
	    			'edit_form'   => $editForm->createView(),
	    			'delete_form' => $deleteForm->createView(),
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
	    			 
	    	));	    	
	    }else{
	    	$flashBag = $this->get('session')->getFlashBag();
	    	$flashBag->get('notice');
	    	$flashBag->set('notice', "Vous devez être connecté pour consulter cette page.");
	    	
	    	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
	    			'modes'      => $this->modes,
	    			'difficulties' => $this->difficulties,
	    	));
	    }
    }
    
    private function createDeleteForm($userId)
    {
    	return $this->createFormBuilder(array('user_id' => $userId, 'modes' => $this->modes, 'difficulties' => $this->difficulties,))
    	->add('user_id', 'hidden')
    	->add('currentpassword', 'password', array(
        				'mapped'		  => false
        		))
    	->getForm()
    	;
    }
    
    public function listGamesAction()
    {
    	$this->loadData();
    	if( $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
	    	$player = $this->get('security.context')->getToken()->getUser();
	    	$games = $this->manager->getRepository('MGMemoryGameBundle:Result')->getResultsGamesByUserId($player, null, 10);
	    	
	    	if (!$games) {
	    		$flashBag = $this->get('session')->getFlashBag();
		    	$flashBag->get('notice'); // gets message and clears type
		    	$flashBag->set('notice', "Vous n'avez pas encore de scores.");
	    	}
	    	    	
	    	return $this->render('MGMemoryGameBundle:User:scores.html.twig', array(
	    			'games'      => $games,
	    			'modes'      => $this->modes,
		    		'difficulties' => $this->difficulties,
	    	));
	    } else {
	    		$flashBag = $this->get('session')->getFlashBag();
	    		$flashBag->get('notice');
	    		$flashBag->set('notice', "Vous devez être connecté pour consulter cette page.");
		    	
		    	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
		    			'modes'      => $this->modes,
		    			'difficulties' => $this->difficulties,
		    	));
    	}
	}

}