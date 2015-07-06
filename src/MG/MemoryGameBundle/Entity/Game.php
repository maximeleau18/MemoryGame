<?php
/**************************************************************************
 * Game.php, MemoryGame
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
 * Game entity
 * 
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\GameRepository")
 * 
 */
class Game{
	
	/**
	 * Game Unique ID.
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	private $id;
		
	/**
	 * Game Played date
	 * 
	 * @ORM\Column(name="played_at", type="datetime", nullable=false)
	 * @var \DateTime
	 */
	private $playedAt;
	
	/**
	 * Game Difficulty
	 * 
 	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\Difficulty", inversedBy="$games")
     * @ORM\JoinColumn(name="difficulty_id", referencedColumnName="id")
	 * @var \MG\MemoryGameBundle\Entity\Difficulty
	 */
	private $difficulty;
	
	/**
	 * Game Mode
	 *
	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\Mode", inversedBy="$games")
	 * @ORM\JoinColumn(name="mode_id", referencedColumnName="id")
	 * @var \MG\MemoryGameBundle\Entity\Mode
	 */
	private $mode;
	
	/**
	 * Get ID
	 * 
	 * @return Game ID
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get Played date
	 * 
	 * @return The Played date
	 */
	public function getPlayedAt() {
		return $this->playedAt;
	}
	
	/**
	 * Set Played date
	 * 
	 * @param \DateTime $playedAt        	
	 */
	public function setPlayedAt(\DateTime $playedAt) {
		$this->playedAt = $playedAt;
		return $this;
	}	
	

    /**
     * Set Difficulty
     *
     * @param \MG\MemoryGameBundle\Entity\Difficulty $difficulty
     * @return Game
     */
    public function setDifficulty(\MG\MemoryGameBundle\Entity\Difficulty $difficulty = null)
    {
        $this->difficulty = $difficulty;

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
     * Set mode
     *
     * @param \MG\MemoryGameBundle\Entity\Mode $mode
     * @return Game
     */
    public function setMode(\MG\MemoryGameBundle\Entity\Mode $mode = null)
    {
        $this->mode = $mode;

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
}
