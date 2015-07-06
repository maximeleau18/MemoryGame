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
	 * @ORM\Column(type="smallint")
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @var integer
	 */
	private $id;
	
	/**
	 * RecordsBook Player username
	 * 
	 * @ORM\Column(type="string", length=150, nullable=false)
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner un pseudonyme."
	 * )
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "50",
	 *      minMessage = "Le pseudonyme doit faire au moins {{ limit }} caractères.",
	 *      maxMessage = "Le pseudonyme ne peut pas être plus long que {{ limit }} caractères."
	 * )
	 * @var string
	 */
	private $playerUsername;
	
	/**
	 * RecordsBook Realized time
	 * 
	 * @ORM\Column(type="float", nullable=false, columnDefinition="float unsigned")
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
	 * Get Player username
	 * 
	 * @return The Player username
	 */
	public function getPlayerUsername() {
		return $this->playerUsername;
	}
	
	/**
	 * Set Player username
	 * 
	 * @param $playerUsername
	 */
	public function setPlayerUsername($playerUsername) {
		$this->playerUsername = $playerUsername;
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
	
}
