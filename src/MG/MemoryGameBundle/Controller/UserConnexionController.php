<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use MG\MemoryGameBundle\Entity\HumanPlayer;

class UserConnexionController extends Controller
{
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
        	return $this->render('MGMemoryGameBundle:UserConnexion:connexion.html.twig', array(
        			// last username entered by the user
        			'last_login' => $session->get(SecurityContext::LAST_USERNAME),
        			'error'         => $error
        		 	));
        }
    }
}