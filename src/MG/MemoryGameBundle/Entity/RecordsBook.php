<?php
/**************************************************************************
 * RecordsBook.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : July 1, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * RecordsBook entity
 * 
 * @ORM\Table(name="records_book")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\RecordsBookRepository")
 * 
 */
class RecordsBook{
	
	/**
	 * RecordsBook Unique ID.
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	private $id;
	
	/**
	 * RecordsBook HumanPlayer
	 *
	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\HumanPlayer", inversedBy="recordsBooks")
	 * @ORM\JoinColumn(name="human_player_id" , referencedColumnName="id" , nullable=false, onDelete="CASCADE")
	 * @var \MG\MemoryGameBundle\Entity\HumanPlayer
	 */
	private $player;
	
	/**
	 * RecordsBook Realized time
	 * 
	 * @ORM\Column(type="float", nullable=false, columnDefinition="float unsigned not null")
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner le temps réalisé."
	 * )
	 * @var float
	 */
	private $time;
	
	/**
	 * RecordsBook Inserted date
	 * 
	 * @ORM\Column(name="inserted_at", type="datetime", nullable=false)
	 * @var \DateTime
	 */
	private $insertedAt;
		
	/**
	 * RecordsBook Difficulty
	 *
	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\Difficulty")
	 * @ORM\JoinColumn(name="difficulty_id", referencedColumnName="id", nullable=false)
	 * @var \MG\MemoryGameBundle\Entity\Difficulty
	 */
	private $difficulty;
	
	/**
	 *  RecordsBook Mode
	 *
	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\Mode")
	 * @ORM\JoinColumn(name="mode_id", referencedColumnName="id", nullable=false)
	 * @var \MG\MemoryGameBundle\Entity\Mode
	 */
	private $mode;
	
	/**
	 * Get ID
	 * 
	 * @return RecordsBook ID
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set ID
	 * 
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Get Player
	 * 
	 * @return \MG\MemoryGameBundle\Entity\HumanPlayer
	 */
	public function getPlayer() {
		return $this->player;
	}
	
	/**
	 * Set Player
	 * 
	 * @param \MG\MemoryGameBundle\Entity\HumanPlayer $player
     * @return RecordsBook
	 */
	public function setPlayer($player) {
		$this->player = $player;
		
		return $this;
	}
	
	/**
	 * Get Realized time
	 * 
	 * @return The Realized time
	 */
	public function getTime() {
		return $this->time;
	}
	
	/**
	 * Set Realized time
	 * 
	 * @param $time
	 */
	public function setTime($time) {
		$this->time = $time;
		return $this;
	}
	
	/**
	 * Get Inserted date
	 * 
	 * @return The Inserted date
	 */
	public function getInsertedAt() {
		return $this->insertedAt;
	}
	
	/**
	 * Set Inserted date
	 * 
	 * @param \DateTime $insertedAt        	
	 */
	public function setInsertedAt(\DateTime $insertedAt) {
		$this->insertedAt = $insertedAt;
		return $this;
	}	
	
	/**
	 * Get Difficulty
	 *
	 * @return \MG\MemoryGameBundle\Entity\Difficulty
	 */
	public function getDifficulty()
	{
		return $this->difficulty;
	}
	
	/**
     * Set Difficulty
     *
     * @param \MG\MemoryGameBundle\Entity\Difficulty $difficulty
     * @return RecordsBook
     */
    public function setDifficulty(\MG\MemoryGameBundle\Entity\Difficulty $difficulty = null)
    {
        $this->difficulty = $difficulty;

        return $this;
    }
    
    /**
     * Get mode
     *
     * @return \MG\MemoryGameBundle\Entity\Mode
     */
    public function getMode()
    {
    	return $this->mode;
    }

	/**
     * Set mode
     *
     * @param \MG\MemoryGameBundle\Entity\Mode $mode
     * @return RecordsBook
     */
    public function setMode(\MG\MemoryGameBundle\Entity\Mode $mode = null)
    {
        $this->mode = $mode;

        return $this;
    }
}
