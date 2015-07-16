<?php
/**************************************************************************
 * Board.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : July 13, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\Entity\GameEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\SmallIntType;

/**
 * Board entity
 *  
 */
class Board{
	
	/**
	 * Board Unique ID.
	 * 
	 * @var smallint
	 */
	private $id;
	
	
	/**
	 * Board Number of cards
	 * 
	 * @var smallint
	 */
	private $nbCards;
	
	/**
	 * Board Timer
	 * 
	 * @var float
	 */
	private $timer;
	
	/**
	 * Board Cards
	 * 
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $cards;
	
		
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->cards = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * Get Board ID
	 * 
	 * @return Unique ID
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set Board ID
	 *
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
		
		return $this;
	}
	
	/**
	 * Get Board Number of Cards
	 *
	 * @return The number of cards
	 */
	public function getNbCards() {
		return $this->nbCards;
	}
	
	/**
	 * Set Board Number of Cards
	 *
	 * @param $nbCards
	 */
	public function setNbCards($nbCards) {
		$this->nbCards = $nbCards;
		
		return $this;
	}
		
	/**
	 * Get Board timer
	 *
	 * @return The timer
	 */
	public function getTimer() {
		return $this->timer;
	}
	
	/**
	 * Set Board timer
	 * 
	 * @param $timer
	 */
	public function setTimer($timer) {
		$this->timer = $timer;
		return $this;
	}
	
	/**
	 * Add card
	 *
	 * @param \MG\MemoryGameBundle\Entity\GameEntity\Card $card
	 * @return Board
	 */
	public function addCard(\MG\MemoryGameBundle\Entity\GameEntity\Card $card)
	{
		$this->cards[] = $card;
	
		return $this;
	}
	
	/**
	 * Remove card
	 *
	 * @param \MG\MemoryGameBundle\Entity\GameEntity\Card $card
	 */
	public function removeCard(\MG\MemoryGameBundle\Entity\GameEntity\Card $card)
	{
		$this->cards->removeElement($card);
	}
	
	/**
	 * Get cards
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getCards()
	{
		return $this->cards;
	}
}
