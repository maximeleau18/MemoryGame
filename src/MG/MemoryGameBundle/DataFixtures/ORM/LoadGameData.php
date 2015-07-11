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
    	
    	$game_02 = $this->makeGame(New \DateTime("2015-05-06 12:45:12.000"), $this->getReference("mode-solo"), $this->getReference("intermediaire"));
    	$this->manager->persist($game_02);
    	$this->manager->flush();
    	
    	$game_03 = $this->makeGame(New \DateTime("2015-06-24 22:33:10.000"), $this->getReference("mode-solo"), $this->getReference("intermediaire"));
    	$this->manager->persist($game_03);
    	$this->manager->flush();    	

    	$game_04 = $this->makeGame(New \DateTime("2015-04-19 21:01:25.000"), $this->getReference("mode-solo"), $this->getReference("intermediaire"));
    	$this->manager->persist($game_04);
    	$this->manager->flush();
    	
    	$this->setReference("game_01", $game_01);
    	$this->setReference("game_02", $game_02);
    	$this->setReference("game_03", $game_03);  
    	$this->setReference("game_04", $game_04);    	
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
		$game->setDifficulty($difficulty);
		$game->setMode($mode);
				
        return $game;
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