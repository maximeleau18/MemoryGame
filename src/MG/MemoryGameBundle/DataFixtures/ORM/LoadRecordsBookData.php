<?php
/**************************************************************************
 * LoadRecordsBookData.php, MemoryGame
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
use MG\MemoryGameBundle\Entity\RecordsBook;
use Doctrine\Common\Collections\ArrayCollection;

class LoadRecordsBookData extends AbstractFixture implements OrderedFixtureInterface
{

	protected $manager;
	
    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     */
    public function load(ObjectManager $manager)
    {
    	$this->manager = $manager;
    	 
    	// Make differents recordsbook entries	
    	$recordsBook_01 = $this->makeRecordsBook($this->getReference("result_01"), New \DateTime());    	
    	$recordsBook_02 = $this->makeRecordsBook($this->getReference("result_02"), New \DateTime());  	
    	$recordsBook_03 = $this->makeRecordsBook($this->getReference("result_03"), New \DateTime());    	
    	$recordsBook_04 = $this->makeRecordsBook($this->getReference("result_04"), New \DateTime()); 	
    	
    	$this->manager->persist($recordsBook_01);
    	$this->manager->persist($recordsBook_02);
    	$this->manager->persist($recordsBook_03);
    	$this->manager->persist($recordsBook_04);
    	$this->manager->flush();
    }

  /**
   * Create a records book entry
   * 
   * @return \MG\MemoryGameBundle\Entity\RecordsBook
   */
    protected function makeRecordsBook($result, $insertedAt)
    {
        $recordsbook = New RecordsBook();
		$recordsbook->setPlayer($result->getPlayer());
		$recordsbook->setTime($result->getTime());
		$recordsbook->setDifficulty($result->getGame()->getDifficulty());
		$recordsbook->setMode($result->getGame()->getMode());
		$recordsbook->setInsertedAt($insertedAt);
        return $recordsbook;
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\Common\DataFixtures\OrderedFixtureInterface::getOrder()
     */
    public function getOrder()
    {
        return 7;
    }
}