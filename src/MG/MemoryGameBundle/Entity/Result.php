<?php
/**************************************************************************
 * Result.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : June 30, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\DBAL\Types\BooleanType;

/**
 * Mode result
 * 
 * @ORM\Table(name="game_result")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\ResultRepository")
 * 
 */
class Result{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\Game")
	 */
	private $game;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="MG\MemoryGameBundle\Entity\Player")
	 * @ORM\JoinColumn(onDelete="CASCADE"))
	 */
	private $player;
	
	/**
	 * Result Time
	 *
	 * @ORM\Column(type="float", nullable=true)
	 * @var float
	 */
	private $time;
	
	/**
	 * Player Result rank
	 *
	 * @ORM\Column(name="result_rank", type="smallint", nullable=false, columnDefinition="smallint unsigned not null")
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner le classement obtenu."
	 * )
	 * @Assert\Range(
	 *      min = 1,
	 *      minMessage = "Vous ne pouvez pas renseigner un classement inférieur à 1."
	 * )
	 * @var smallint
	 */
	private $rank;
	
	/**
	 * Is winner of the game
	 * 
	 * @ORM\Column(type="boolean", nullable=false)
	 * @Assert\NotBlank(
	 * 		message = "Vous devez indiquer si vous avez perdu ou gagné."
	 * )
	 * @var boolean
	 */
	private $isWinner;
	

	/**
	 *
	 * @return \MG\MemoryGameBundle\Entity\Game
	 */
	public function getGame() {
		return $this->game;
	}
	
	/**
	 *
	 * @param \MG\MemoryGameBundle\Entity\Game $game
	 */
	public function setGame($game) {
		$this->game = $game;
		return $this;
	}
	
	/**
	 *
	 * @return \MG\MemoryGameBundle\Entity\Player
	 */
	public function getPlayer() {
		return $this->player;
	}
	
	/**
	 *
	 * @param \MG\MemoryGameBundle\Entity\Player $player
	 */
	public function setPlayer($player) {
		$this->player = $player;
		return $this;
	}
	
	/**
	 *	Get result time
	 *
	 * @return the float
	 */
	public function getTime() {
		return $this->time;
	}
	
	/**
	 * Set result time
	 *
	 * @param $time
	 */
	public function setTime($time) {
		$this->time = $time;
		return $this;
	}
	
	/**
	 * Get player rank
	 *
	 * @return the smallint
	 */
	public function getRank() {
		return $this->rank;
	}
	
	/**
	 * Set player rank
	 * 
	 * @param $rank
	 */
	public function setRank($rank) {
		$this->rank = $rank;
		return $this;
	}
	
	/**
	 * Get Is Winner
	 *
	 * @return the boolean
	 */
	public function getIsWinner() {
		return $this->isWinner;
	}
	
	/**
	 * Set Is Winner
	 * 
	 * @param $isWinner
	 */
	public function setIsWinner($isWinner) {
		$this->isWinner = $isWinner;
		return $this;
	}
		
}
