<?php
/**************************************************************************
 * LoadHumanPlayerData.php, MemoryGame
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
use MG\MemoryGameBundle\Entity\HumanPlayer;

class LoadHumanPlayerData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	    	 
    	// Make Humanlayers
    	$humanPlayer01 = $this->makeHumanPlayer("maximeleau", "maximeleau", New \DateTime("1988-08-18"));
    	$humanPlayer02 = $this->makeHumanPlayer("charlyriviere", "charlyriviere", New \DateTime("1992-10-06"));
    	$humanPlayer03 = $this->makeHumanPlayer("rudyvanoost", "rudyvanoost", New \DateTime("1991-08-26"));
    	$humanPlayer04 = $this->makeHumanPlayer("sophiebeaune", "sophiebeaune", New \DateTime("1990-01-30"));
    	
    	$this->manager->persist($humanPlayer01);
    	$this->manager->persist($humanPlayer02);
    	$this->manager->persist($humanPlayer03);
    	$this->manager->persist($humanPlayer04);
    	
    	$this->manager->flush();

    }

  /**
   * Create a human player
   * 
   * @return \MG\MemoryGameBundle\Entity\ComputerPlayer
   */
    protected function makeHumanPlayer($login, $password, $birthDate)
    {
        $humanPlayer = New HumanPlayer();
        $humanPlayer->setLogin($login);
        $humanPlayer->setPassword($password);
        $humanPlayer->setBirthDate($birthDate);
        $humanPlayer->setCreatedAt(New \DateTime());        

        return $humanPlayer;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 2;
    }
}