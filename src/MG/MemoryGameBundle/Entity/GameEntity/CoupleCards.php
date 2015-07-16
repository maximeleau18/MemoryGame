<?php
/**************************************************************************
 * CoupleCards.php, MemoryGame
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
 * CoupleCards entity
 *  
 */
class CoupleCards{
	
	/**
	 * CoupleCards Unique ID.
	 * 
	 * @var smallint
	 */
	private $id;
	
	
	/**
	 * CoupleCards IsDiscovered
	 * 
	 * @var boolean
	 */
	private $isDiscovered;
	
	/**
	 * CoupleCards State
	 * 
	 * @var \MG\MemoryGameBundle\Entity\GameEntity\State
	 */
	private $state;
	
	/**
	 * CoupleCards Cards
	 * 
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $cards;
	

	/**
	 * Constructor
	 * 
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
	 * Get IsDiscovered
	 *
	 * @return If The CoupleCards is discovered
	 */
	public function getIsDiscovered() {
		return $this->nbCards;
	}
	
	/**
	 * Set IsDiscovered
	 *
	 * @param $isDiscovered
	 */
	public function setIsDiscovered($isDiscovered) {
		$this->isDiscovered = $isDiscovered;
		
		return $this;
	}
		
	/**
	 * Get CouleCards State
	 *
	 * @return \MG\MemoryGameBundle\Entity\GameEntity\State
	 */
	public function getState() {
		return $this->state;
	}
	
	/**
	 * Set CouleCards State
	 * 
	 * @param \MG\MemoryGameBundle\Entity\GameEntity\State
	 */
	public function setState($state) {
		$this->state = $state;
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
		$card->setCoupleCards($this);
	
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

