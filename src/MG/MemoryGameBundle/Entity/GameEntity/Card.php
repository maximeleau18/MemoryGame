<?php
/**************************************************************************
 * Card.php, MemoryGame
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
 * Card entity
 *  
 */
class Card{
	
	/**
	 * Card Unique ID.
	 * 
	 * @var smallint
	 */
	private $id;
	
	
	/**
	 * Card Image Name
	 * 
	 * @var string
	 */
	private $imgName;
	
	/**
	 * Card Locked
	 * 
	 * @var boolean
	 */
	private $locked;
	
	/**
	 * Card Visible
	 * 
	 * @var boolean
	 */
	private $visible;
	
	/**
	 * 
	 * @var \MG\MemoryGameBundle\Entity\GameEntity\CoupleCards
	 */
	private $coupleCards;
	
		
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->coupleCards = new CoupleCards();
	}
	
	/**
	 * Get Card ID
	 * 
	 * @return Unique ID
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set Card ID
	 *
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
		
		return $this;
	}
	
	/**
	 * Get Card Image Name
	 *
	 * @return The card image name
	 */
	public function getImgName() {
		return $this->imgName;
	}
	
	/**
	 * Set Card Image Name
	 * 
	 * @param $imgName
	 */
	public function setImgName($imgName) {
		$this->imgName = $imgName;
		
		return $this;
	}
	
	/**
	 * Get Card Visible
	 *
	 * @return If this card is visible
	 */
	public function getVisible() {
		return $this->visible;
	}
	
	/**
	 * Set Card Visible
	 *
	 * @param $visible
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
		return $this;
	}
		
	/**
	 * Get Card Locked
	 *
	 * @return If this card is locked
	 */
	public function getLocked() {
		return $this->locked;
	}
	
	/**
	 * Set Card Locked
	 * 
	 * @param $locked
	 */
	public function setLocked($locked) {
		$this->locked = $locked;
		return $this;
	}
	
	/**
	 * Get Couple Cards Parent
	 *
	 * @return \MG\MemoryGameBundle\Entity\GameEntity\CoupleCards
	 */
	public function getCoupleCards()
	{
		return $this->coupleCards;
	}
	
	/**
	 * Set Couple Cards Parent
	 *
	 * @param \MG\MemoryGameBundle\Entity\GameEntity\CoupleCards
	 */
	public function setCoupleCards($coupleCards) {
		$this->coupleCards = $coupleCards;
		return $this;
	}
}
