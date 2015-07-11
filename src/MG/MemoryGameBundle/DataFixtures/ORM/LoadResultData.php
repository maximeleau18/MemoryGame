<?php
/**************************************************************************
 * LoadResultData.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : July 09, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MG\MemoryGameBundle\Entity\Result;
use Doctrine\Common\Collections\ArrayCollection;

class LoadResultData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	 
    	// Make differents results    	
    	$result_game_01 = $this->makeResult($this->getReference("game_01"), $this->getReference("human_01"), true, 1, 98.0);
    	$this->manager->persist($result_game_01);
    	$this->manager->flush();    	
    	$result_game_02 = $this->makeResult($this->getReference("game_01"), $this->getReference("computer_01"), false, 2, null);
    	$this->manager->persist($result_game_02);
    	$this->manager->flush();
    	    	 
    	$result_game_03 = $this->makeResult($this->getReference("game_02"), $this->getReference("human_03"), true, 1, 125.5);
    	$this->manager->persist($result_game_03);
    	$this->manager->flush();
    	$result_game_04 = $this->makeResult($this->getReference("game_02"), $this->getReference("computer_01"), false, 2, null);
    	$this->manager->persist($result_game_04);
    	$this->manager->flush();
    	    	
    	$result_game_05 = $this->makeResult($this->getReference("game_03"), $this->getReference("human_03"), true, 1, 110.2);
    	$this->manager->persist($result_game_05);
    	$this->manager->flush();
    	$result_game_06 = $this->makeResult($this->getReference("game_03"), $this->getReference("computer_01"), false, 2, null);
    	$this->manager->persist($result_game_06);
    	$this->manager->flush();

    	$result_game_07 = $this->makeResult($this->getReference("game_04"), $this->getReference("human_03"), true, 1, 100.8);
    	$this->manager->persist($result_game_07);
    	$this->manager->flush();
    	$result_game_08 = $this->makeResult($this->getReference("game_04"), $this->getReference("computer_01"), false, 2, null);
    	$this->manager->persist($result_game_08);
    	$this->manager->flush();
    	
    	$this->setReference("result_01", $result_game_01);
    	$this->setReference("result_02", $result_game_03);
    	$this->setReference("result_03", $result_game_05);
    	$this->setReference("result_04", $result_game_07);
    }
    
    /**
     * Create a result
     * 
     * @return \MG\MemoryGameBundle\Entity\Result
     * 
     */
    protected function makeResult($game, $player, $isWinner, $rank, $time)
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
        return 6;
    }
}