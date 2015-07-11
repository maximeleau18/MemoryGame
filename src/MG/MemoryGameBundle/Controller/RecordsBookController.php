<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MG\MemoryGameBundle\Entity\HumanPlayer;
use MG\MemoryGameBundle\Entity\RecordsBook;
use MG\MemoryGameBundle\Entity\Mode;

class RecordsBookController extends Controller
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
	
    public function listRecordsAction($id)
    {    
    	$this->loadData();
    	$mode = $this->manager->getRepository('MGMemoryGameBundle:Mode')->findOneById($id);
    	$records = $this->manager->getRepository('MGMemoryGameBundle:RecordsBook')->getRecordsBookByMode($mode, null, 10);
    	
    	if (!$records) {
    		$flashBag = $this->get('session')->getFlashBag();
    		$flashBag->get('notice'); // gets message and clears type
    		$flashBag->set('notice', "Il n'existe aucun score enregistré pour ce mode.");
    	}
    		
    	return $this->render('MGMemoryGameBundle:RecordsBook:records.html.twig', array(
    			'records'      => $records,
    			'modes'        => $this->modes,
    			'difficulties' => $this->difficulties,
    	));
    }
    

    public function listAllRecordsAction()
    {
    	$this->loadData();
    	$records = $this->manager->getRepository('MGMemoryGameBundle:RecordsBook')->findAllRecordsOrderedByMode(null, 10);
    	 
    	if (!$records) {
    		$flashBag = $this->get('session')->getFlashBag();
    		$flashBag->get('notice'); // gets message and clears type
    		$flashBag->set('notice', "Il n'existe aucun score enregistré.");
    	}
    
    	return $this->render('MGMemoryGameBundle:RecordsBook:records.html.twig', array(
    			'records'      => $records,
    			'modes'        => $this->modes,
    			'difficulties' => $this->difficulties,
    	));
    }
}
