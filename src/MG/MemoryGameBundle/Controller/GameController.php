<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MG\MemoryGameBundle\Entity\Game;
use MG\MemoryGameBundle\Form\DifficultyType;
use MG\MemoryGameBundle\Entity\Difficulty;
use Doctrine\ORM\Query\AST\Functions\SqrtFunction;
use MG\MemoryGameBundle\Entity\GameEntity\State;
use MG\MemoryGameBundle\Entity\GameEntity\Board;
use MG\MemoryGameBundle\Entity\GameEntity\Card;
use MG\MemoryGameBundle\Entity\GameEntity\CoupleCards;
use Symfony\Component\HttpFoundation\Response;
use MG\MemoryGameBundle\Entity\Result;
use MG\MemoryGameBundle\Entity\ComputerPlayer;
use MG\MemoryGameBundle\Entity\RecordsBook;

class GameController extends Controller
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
	
    public function newGameAction($modeId)
    {        	
    	$this->loadData();   	
    	$mode = $this->manager->getRepository('MGMemoryGameBundle:Mode')->find($modeId);

    	$request = $this->getRequest();
    	$session = $request->getSession();
    	$session->remove('game_started');
    	 
    	if (!$mode) {
    		throw $this->createNotFoundException('Aucun mode de jeu portant cet id trouvé.');
    	}
    	
    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') == false ){
    		$flashBag = $this->get('session')->getFlashBag();
    		$flashBag->get('notice');
    		$flashBag->set('notice', "Vous pouvez jouer sans vous connecter, toutefois votre score ne sera pas mémorisé.");
    	}
    	
    	$difficultyForm = $this->createForm(new DifficultyType(), new Difficulty());    		
    	
    	return $this->render('MGMemoryGameBundle:Game:newSoloGame.html.twig', array(
    			'modes'      => $this->modes,
    			'difficulties' => $this->difficulties,
    			'difficulty_form' => $difficultyForm->createView(),
    			'choisen_mode' => $mode,
    	));
    }

    public function stopGameAction()
    {
       	$this->loadData();
    	$flashBag = $this->get('session')->getFlashBag();
     	$flashBag->get('notice');
    	$flashBag->set('notice', "Vous avez arrêté le jeu. Celui-ci n'est donc pas sauvegardé.");
    	
    	$request = $this->getRequest();
    	$session = $request->getSession();
    	$session->remove('game_started');
    	
    	return $this->render('MGMemoryGameBundle:Default:index.html.twig', array(
    			'modes'      => $this->modes,
    			'difficulties' => $this->difficulties,
    	));
	}

	public function startGameAction()
	{
		$this->loadData();
    	$request = $this->getRequest();
		$session = $request->getSession();
		
		// If player is authenticated
		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
			$difficulty = $this->manager->getRepository('MGMemoryGameBundle:Difficulty')->find($session->get('difficulty_choisen')->getId());
			$mode = $this->manager->getRepository('MGMemoryGameBundle:Mode')->find($session->get('mode_choisen')->getId());
			
			if (!$mode) {
				throw $this->createNotFoundException('Aucun mode de jeu portant cet id trouvé.');
			}
			if (!$difficulty) {
				throw $this->createNotFoundException('Aucune difficulté de jeu portant cet id trouvé.');
			}
			
			$game = new Game();
			$game->setDifficulty($difficulty);
			$game->setMode($mode);
			$game->setPlayedAt(new \DateTime());
			
			$this->manager->persist($game);
			$this->manager->flush();
			 
			// We store the game in session	
			$session->set('game', $game);
		}
		
		$session->set('game_started', true);
		
		 
		return $this->render('MGMemoryGameBundle:Game:tabGame.html.twig', array(
				'modes'      => $this->modes,
				'difficulties' => $this->difficulties,
		));
	}
	
    public function launchGameAction($modeId)
    {
    	 $this->loadData();
    	 $mode = $this->manager->getRepository('MGMemoryGameBundle:Mode')->find($modeId);
    	 
    	 if (!$mode) {
    	 	throw $this->createNotFoundException('Aucun mode de jeu portant cet id trouvé.');
    	 }

    	 $request = $this->getRequest();
    	 $difficultyForm = $this->createForm(new DifficultyType(), new Difficulty());
    	 
    	 $difficultyForm->bind($request);
    	  
    	 if ($difficultyForm->isValid()) {
    	 	$difficulty = $difficultyForm->get('label')->getData();
    	 	if (!$difficulty){
    	 		throw $this->createNotFoundException('Aucune difficulté de jeu portant cet id trouvé.');    	 		
    	 	}	

    	 	$session = $request->getSession();
    	 	
    	 	return $this->launchGame($mode, $difficulty, $session);
    	 }
    	 
    	 return $this->render('MGMemoryGameBundle:Game:newSoloGame.html.twig', array(
	    		'modes'      => $this->modes,
	    		'difficulties' => $this->difficulties,
	    		'difficulty_form' => $difficultyForm->createView(),
	    		'choisen_mode' => $mode,
	    ));
    }
    
    private function launchGame($mode, $difficulty, $session)
    {    	
    	$board = New Board();
    	$board->setId(1);
    	$board->setNbCards($difficulty->getNbCoupleCards() * 2);
    	$board->setTimer($difficulty->getTimer());
    	
    	for ($i = 1; $i <= $difficulty->getNbCoupleCards(); $i++){    		
    		$hidden_state = New State();
    		$hidden_state->setId(2);
    		$hidden_state->setLabel('hidden');
    		$coupleCards = New CoupleCards(); 
    		$coupleCards->setId($i);
    		$coupleCards->setIsDiscovered(false);
    		$coupleCards->setState($hidden_state);
    		$card_1 = New Card();
    		$card_1->setId($i);
    		$card_1->setImgName('img_' . $i);
    		$card_1->setLocked(false);
    		$card_1->setVisible(false);
    		$card_2 = New Card();
    		$card_2->setId($i + $difficulty->getNbCoupleCards());
    		$card_2->setImgName('img_' . $i);
    		$card_2->setLocked(false);
    		$card_2->setVisible(false);
    		$coupleCards->addCard($card_1);
    		$coupleCards->addCard($card_2);    	
    		$board->addCard($card_1);	
    		$board->addCard($card_2);
    	}
    	
    	$tab = array();
    	for ($x = 1; $x <= sqrt($difficulty->getNbCoupleCards() * 2); $x++){
    		for ($z = 1; $z <= sqrt($difficulty->getNbCoupleCards() * 2); $z++){
    			if (count($board->getCards()) > 0){
    				$cardId = rand(1, count($board->getCards()));
    				foreach ($board->getCards() as $c){
    					if ($c->getId() === $cardId){
    						break;
    					}
    				}
	    			$tab[$x][$z] = $c;
	    			$board->removeCard($c);
    			}
    		}
    	}
    	   	
    	$session->set('game_tab', $tab);
    	$session->set('difficulty_choisen', $difficulty);
    	$session->set('mode_choisen', $mode); 	
    	
    	return $this->render('MGMemoryGameBundle:Game:myGame.html.twig', array(
    			'modes'      => $this->modes,
    			'difficulties' => $this->difficulties,
    	));
    }
    
    public function checkCardAction()
    {
    	 $this->loadData();    	 
    	 $request = $this->getRequest();
    	 $session = $request->getSession();
    	 
    	 if($request->isXmlHttpRequest()){
	    	 $cardId = $request->request->get('idcard');
	    	 $timer = $request->request->get('timer');
	    	 $board = $session->get('game_tab');    	 
	    	 $selectedCard = $this->getCardById($board, $cardId);

		    	 for($i = 1; $i <= count($board); $i++){
		    	 	for ($j = 1; $j <= count($board[1]); $j++){
		    	 		$currentCard = $board[$i][$j];
		    	 		if ($currentCard->getLocked() == false){
		    	 			if ($selectedCard->getVisible()){
		    	 				if ($currentCard->getVisible()){
		    	 					if ($currentCard->getId() !== $selectedCard->getId()){
		    	 						if ($currentCard->getCoupleCards()->getId() === $selectedCard->getCoupleCards()->getId()){
		    	 							$selectedCard->setVisible(true);
		    	 							$currentCard->setLocked(true);
		    	 							$selectedCard->setLocked(true);
		    	 							$session->set('game_tab', $board);
		    	 							 
		    	 							if ($this->checkEndGame($board, $timer) == false){	    	 						
				    	 						return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
				    	 								'modes'      => $this->modes,
				    	 								'difficulties' => $this->difficulties,
				    	 						));
			    	 						}else{
									    	 	$session->set('go_to_homepage', true);
									    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
									    	 			'modes'      => $this->modes,
									    	 			'difficulties' => $this->difficulties,
									    	 	));
									    	 }
		    	 						}else{
		    	 							$currentCard->setVisible(false);
		    	 							$selectedCard->setVisible(true);
		    	 							$session->set('game_tab', $board);
		    	 					
			    	 						if ($this->checkEndGame($board, $timer) == false){	    	 						
				    	 						return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
				    	 								'modes'      => $this->modes,
				    	 								'difficulties' => $this->difficulties,
				    	 						));
			    	 						}else{
									    	 	$session->set('go_to_homepage', true);
									    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
									    	 			'modes'      => $this->modes,
									    	 			'difficulties' => $this->difficulties,
									    	 	));
									    	 }
		    	 						}
		    	 					}else{
		    	 						$selectedCard->setVisible(true);
		    	 						$session->set('game_tab', $board);
	
		    	 						if ($this->checkEndGame($board, $timer) == false){	    	 						
			    	 						return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
			    	 								'modes'      => $this->modes,
			    	 								'difficulties' => $this->difficulties,
			    	 						));
		    	 						}else{
								    	 	$session->set('go_to_homepage', true);
								    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
								    	 			'modes'      => $this->modes,
								    	 			'difficulties' => $this->difficulties,
								    	 	));
								    	 }
		    	 					}
		    	 				}else{
		    	 					if($currentCard->getId() === $selectedCard->getId()){
		    	 						$session->set('game_tab', $board);
		    	 						
		    	 						if ($this->checkEndGame($board, $timer) == false){	    	 						
			    	 						return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
			    	 								'modes'      => $this->modes,
			    	 								'difficulties' => $this->difficulties,
			    	 						));
		    	 						}else{
								    	 	$session->set('go_to_homepage', true);
								    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
								    	 			'modes'      => $this->modes,
								    	 			'difficulties' => $this->difficulties,
								    	 	));
								    	 }
		    	 					}
		    	 				}
		    	 			}else{
		    	 				if ($currentCard->getVisible()){
		    	 					if ($currentCard->getCoupleCards()->getId() === $selectedCard->getCoupleCards()->getId()){
		    	 						$selectedCard->setVisible(true);
		    	 						$currentCard->setLocked(true);
		    	 						$selectedCard->setLocked(true);
		    	 						$session->set('game_tab', $board);
	
		    	 						if ($this->checkEndGame($board, $timer) == false){	    	 						
			    	 						return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
			    	 								'modes'      => $this->modes,
			    	 								'difficulties' => $this->difficulties,
			    	 						));
		    	 						}else{
								    	 	$session->set('go_to_homepage', true);
								    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
								    	 			'modes'      => $this->modes,
								    	 			'difficulties' => $this->difficulties,
								    	 	));
								    	 }
		    	 					} else{
		    	 						$currentCard->setVisible(false);
		    	 						$selectedCard->setVisible(true);
		    	 						$session->set('game_tab', $board);
	
		    	 						if ($this->checkEndGame($board, $timer) == false){	    	 						
			    	 						return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
			    	 								'modes'      => $this->modes,
			    	 								'difficulties' => $this->difficulties,
			    	 						));
		    	 						}else{
								    	 	$session->set('go_to_homepage', true);
								    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
								    	 			'modes'      => $this->modes,
								    	 			'difficulties' => $this->difficulties,
								    	 	));
								    	 }
		    	 					}
		    	 				}   	 		
		    	 			}
		    	 		}
		    	 	}
		    	 }
		    	 
		    	 $selectedCard->setVisible(true);
		    	 $session->set('game_tab', $board);
		    	  
		    	 if ($this->checkEndGame($board, $timer) == false){
		    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
		    	 			'modes'      => $this->modes,
		    	 			'difficulties' => $this->difficulties,
		    	 	));
		    	 }else{
		    	 	$session->set('go_to_homepage', true);
		    	 	return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
		    	 			'modes'      => $this->modes,
		    	 			'difficulties' => $this->difficulties,
		    	 	));
		    	 }
	    	 }
	    	
	    	 $session->set('go_to_homepage', true);
	    	 return $this->render('MGMemoryGameBundle:Game:boardGame.html.twig', array(
	    	 		'modes'      => $this->modes,
	    	 		'difficulties' => $this->difficulties,
	    	 ));   	  	 
    }
    
    public function checkEndGame($board, $timer){
    	if (($this->countLockedCards($board) == (count($board[1]) * count($board[1]))) && ($timer > 0)){
    		$this->saveWinnerResult($timer);
    		return true;
    	}else{
    		if ($timer <= 0){
    			$this->saveLooserResult($timer);
    			return true;
    		}
    	}
    	return false;
    }
    
    public function saveWinnerResult($timer)
    {
    	$this->loadData();
    	$request = $this->getRequest();
    	$session = $request->getSession();
    	
    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
	    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
	    	$user = $this->manager->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
	    	$computer = $this->manager->getRepository('MGMemoryGameBundle:Player')->find(1);
	    	
	    	if (!$user){
	    		throw $this->createNotFoundException('Impossible de trouver ce joueur.');
	    	}
	    	
	    	$game = $session->get('game');
	    	if ($game){	    		
	    		$game = $this->manager->getRepository('MGMemoryGameBundle:Game')->find($game->getId());
	    		
	    		$result = New Result();
	    		$result->setGame($game);
	    		$result->setTime(new \DateTime());
	    		$result->setPlayer($user);
	    		$result->setIsWinner(true);
	    		$result->setRank(1);
	    		$result->setTime($game->getDifficulty()->getTimer() - $timer);
	    		
	    		$this->manager->persist($result);
	    		$this->manager->flush();   
	
	    		$result = New Result();
	    		$result->setGame($game);
	    		$result->setTime(new \DateTime());
	    		$result->setPlayer($computer);
	    		$result->setIsWinner(false);
	    		$result->setRank(2);
	    		$result->setTime(null);
	    		
	    		$this->manager->persist($result);
	    		$this->manager->flush();    		
	    		
	    		$this->insertRecordsBook($user, $game->getMode(), $game->getDifficulty(), $timer);
	    		
	    		$flashBag = $this->get('session')->getFlashBag();
	    		$flashBag->get('notice');
	    		$flashBag->set('notice', "Vous avez gagné ! Votre score est bien enregistré.");
	    	}
    	}else{
    		$flashBag = $this->get('session')->getFlashBag();
    		$flashBag->get('notice');
    		$flashBag->set('notice', "Vous avez gagné !");
    	}
    	
    	$session->remove('game_started');
    }
    
    public function saveLooserResult()
    {
    	$this->loadData();
    	$request = $this->getRequest();
    	$session = $request->getSession();

    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
	    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
	    	$user = $this->manager->getRepository('MGMemoryGameBundle:HumanPlayer')->find($userId);
	    	$computer = $this->manager->getRepository('MGMemoryGameBundle:Player')->find(1);
	    	
	    	if (!$user){
	    		throw $this->createNotFoundException('Impossible de trouver ce joueur.');
	    	}
	    		    	 
	    	$game = $session->get('game');
	    	if ($game){
	    		$game = $this->manager->getRepository('MGMemoryGameBundle:Game')->find($game->getId());
	    		
	    		$result = New Result();
	    		$result->setGame($game);
	    		$result->setTime(new \DateTime());
	    		$result->setPlayer($user);
	    		$result->setIsWinner(false);
	    		$result->setRank(2);
	    		$result->setTime(null);
	    		
	    		$this->manager->persist($result);
	    		$this->manager->flush();   
	
	    		$result = New Result();
	    		$result->setGame($game);
	    		$result->setTime(new \DateTime());
	    		$result->setPlayer($computer);
	    		$result->setIsWinner(true);
	    		$result->setRank(1);
	    		$result->setTime(null);
	    		
	    		$this->manager->persist($result);
	    		$this->manager->flush();
	    		
	    		$flashBag = $this->get('session')->getFlashBag();
	    		$flashBag->get('notice');
	    		$flashBag->set('notice', "Vous avez perdu !");
	    	}
    	}else{
	    	$flashBag = $this->get('session')->getFlashBag();
	    	$flashBag->get('notice');
	    	$flashBag->set('notice', "Vous avez perdu !");
    	}
	    
    	$session->remove('game_started'); 	
    }
    
    private function insertRecordsBook($user, $mode, $difficulty, $timer){
    	$recordsBook = New RecordsBook();
    	$recordsBook->setPlayer($user);
    	$recordsBook->setMode($mode);
    	$recordsBook->setDifficulty($difficulty);
    	$recordsBook->setTime($difficulty->getTimer() - $timer);
    	$recordsBook->setInsertedAt(New \DateTime());
    	
    	$this->manager->persist($recordsBook);
    	$this->manager->flush();
    }
    
    private function getCardById($tabCards, $cardId)
    {
    	for($i = 1; $i <= count($tabCards); $i++){
    		for ($j = 1; $j <= count($tabCards[1]); $j++){    			
    				if ($tabCards[$i][$j]->getId() == $cardId){
    					return $tabCards[$i][$j];
    				}
    		}
    	}
    	return null;
	}
	
	private function countLockedCards($tabCards)
	{
		$nbCards = 0;
		for($i = 1; $i <= count($tabCards); $i++){
			for ($j = 1; $j <= count($tabCards[1]); $j++){
				if ($tabCards[$i][$j]->getLocked()){
					$nbCards++;
				}
			}
		}
		return $nbCards;
	}

}
