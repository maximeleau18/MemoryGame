<?php
/**************************************************************************
 * LoadDifficultyData.php, MemoryGame
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
use MG\MemoryGameBundle\Entity\Difficulty;

class LoadDifficultyData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	 
    	// Make differents difficulties
    	$difficulty_01 = $this->makeDifficulty("Facile", 8, 180.0);
    	$difficulty_02 = $this->makeDifficulty("Intermédiaire", 18, 360.0);
    	$difficulty_03 = $this->makeDifficulty("Difficile", 32, 900.0);
    	
    	
    	$this->manager->persist($difficulty_01);
    	$this->manager->persist($difficulty_02);
    	$this->manager->persist($difficulty_03);
    	$this->manager->flush();
    	
    	$this->setReference('facile', $difficulty_01);
    	$this->setReference('intermediaire', $difficulty_02);
    	$this->setReference('difficile', $difficulty_03);
    }

  /**
   * Create a difficulty
   * 
   * @return \MG\MemoryGameBundle\Entity\Difficulty
   */
    protected function makeDifficulty($label, $nbCoupleCards, $timer)
    {
        $difficulty = New Difficulty();
        $difficulty->setLabel($label);
        $difficulty->setNbCoupleCards($nbCoupleCards);
        $difficulty->setTimer($timer);

        return $difficulty;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 3;
    }
}