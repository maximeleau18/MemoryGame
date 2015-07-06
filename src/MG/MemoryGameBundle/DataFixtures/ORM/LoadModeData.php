<?php
/**************************************************************************
 * LoadModeData.php, MemoryGame
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
use MG\MemoryGameBundle\Entity\Mode;

class LoadModeData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	 
    	// Make differents modes
    	$mode_01 = $this->makeMode("Solo", 2);
    	$mode_02 = $this->makeMode("Multi-joueurs", 4);
    	
    	
    	$this->manager->persist($mode_01);
    	$this->manager->persist($mode_02);
    	$this->manager->flush();
    	
    	$this->setReference('mode-solo', $mode_01);
    	$this->setReference('mode-multi', $mode_02);
    }

  /**
   * Create a mode
   * 
   * @return \MG\MemoryGameBundle\Entity\Mode
   */
    protected function makeMode($label, $nbMaxPlayers)
    {
        $mode = New Mode();
        $mode->setLabel($label);
        $mode->setNbMaxPlayers($nbMaxPlayers);

        return $mode;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 4;
    }
}