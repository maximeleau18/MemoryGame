<?php
/**************************************************************************
 * State.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : July 13, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\Entity\GameEntity;

use Doctrine\DBAL\Types\SmallIntType;

/**
 * State entity
 *  
 */
class State{
	
	/**
	 * State Unique ID.
	 * 
	 * @var smallint
	 */
	private $id;
	
	
	/**
	 * State Label
	 * 
	 * @var string
	 */
	private $label;
			
	/**
	 * Constructor
	 */
	public function __construct()
	{
	}
	
	/**
	 * Get State ID
	 * 
	 * @return Unique ID
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set State ID
	 *
	 * @param $id
	 */
	public function setId($id) {
		$this->id = $id;
		
		return $this;
	}
	
	/**
	 * Get State Label
	 *
	 * @return The label of the state
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Set State Label
	 * 
	 * @param $label
	 */
	public function setLabel($label) {
		$this->$label = $label;
		
		return $this;
	}
}
