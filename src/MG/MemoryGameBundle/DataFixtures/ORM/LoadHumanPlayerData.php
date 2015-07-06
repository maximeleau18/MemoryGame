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

class LoadHumanPlayerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

	protected $manager;
	
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
	
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
    	
    	$this->setReference('human_01', $humanPlayer01);
    	$this->setReference('human_02', $humanPlayer02);
    	$this->setReference('human_03', $humanPlayer03);
    	$this->setReference('human_04', $humanPlayer04);
    }

  /**
   * Create a human player
   * 
   * @return \MG\MemoryGameBundle\Entity\ComputerPlayer
   */
    protected function makeHumanPlayer($username, $password, $birthDate)
    {
        $humanPlayer = New HumanPlayer();
        $factory = $this->container->get('security.encoder_factory');        
        $encoder = $factory->getEncoder($humanPlayer);
        $passwordEncoded = $encoder->encodePassword($password, $humanPlayer->getSalt());
        $humanPlayer->setPassword($passwordEncoded);
        $humanPlayer->setUsername($username);
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