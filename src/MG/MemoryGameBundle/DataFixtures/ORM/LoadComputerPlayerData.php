<?php
/**************************************************************************
 * LoadComputerPlayerData.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : June 14, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MG\MemoryGameBundle\Entity\ComputerPlayer;

class LoadComputerPlayerData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	 
    	// Make ComputerPlayer
    	$computerPlayer = $this->makeComputerPlayer();
    	
    	$this->manager->persist($computerPlayer);
    	$this->manager->flush();

    }

  /**
   * Create a computer player
   * 
   * @return \MG\MemoryGameBundle\Entity\ComputerPlayer
   */
    protected function makeComputerPlayer()
    {
        $computerPlayer = New ComputerPlayer();

        return $computerPlayer;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 1;
    }
}