<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MG\MemoryGameBundle\Entity\HumanPlayer;

class DefaultController extends Controller
{
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
	
    public function indexAction()
    {        	
    	$this->loadData();

    	$request = $this->getRequest();
    	$session = $request->getSession();
    	$session->remove('go_to_homepage');
    	
    	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
    			'modes'      => $this->modes,
    			'difficulties' => $this->difficulties,
    	));
    }
}
