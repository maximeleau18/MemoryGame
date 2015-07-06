<?php
/**************************************************************************
 * LoadGameData.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : July 06, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MG\MemoryGameBundle\Entity\Game;
use MG\MemoryGameBundle\Entity\Result;
use Doctrine\Common\Collections\ArrayCollection;

class LoadGameData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	 
    	// Make differents games	
    	$game_01 = $this->makeGame(New \DateTime(), $this->getReference("mode-solo"), $this->getReference("facile"));    	    	    	
    	$this->manager->persist($game_01);
    	$this->manager->flush();
    	
    	$result_game_01 = $this->makeGameResult($game_01, $this->getReference("human_01"), true, 1, 98.0);
    	$this->manager->persist($result_game_01);
    	$this->manager->flush();
    	
    	$result_game_01 = $this->makeGameResult($game_01, $this->getReference("computer_01"), false, 2, null);
    	$this->manager->persist($result_game_01);
    	$this->manager->flush();
    	
    	$game_02 = $this->makeGame(New \DateTime("2015-05-06 12:45:12.000"), $this->getReference("mode-solo"), $this->getReference("intermediaire"));
    	$this->manager->persist($game_02);
    	$this->manager->flush();
    	 
    	$result_game_02 = $this->makeGameResult($game_02, $this->getReference("human_03"), true, 1, 125.5);
    	$this->manager->persist($result_game_02);
    	$this->manager->flush();
    	 
    	$result_game_02 = $this->makeGameResult($game_02, $this->getReference("computer_01"), false, 2, null);
    	$this->manager->persist($result_game_02);
    	$this->manager->flush();
    }

  /**
   * Create a game
   * 
   * @return \MG\MemoryGameBundle\Entity\Game
   */
    protected function makeGame($playedAt, $mode, $difficulty)
    {
        $game = New Game();
        $game->setPlayedAt($playedAt);
		$mode->addGame($game);
		$difficulty->addGame($game);
				
        return $game;
    }
    
    /**
     * Create a game result
     * 
     * @return \MG\MemoryGameBundle\Entity\Result
     * 
     */
    protected function makeGameResult($game, $player, $isWinner, $rank, $time)
    {
    	$result = New Result();
    	$result->setGame($game);
    	$result->setPlayer($player);
    	$result->setIsWinner($isWinner);
    	$result->setRank($rank);
    	$result->setTime($time);
    	
    	return $result;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 5;
    }
}