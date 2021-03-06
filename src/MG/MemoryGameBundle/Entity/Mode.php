<?php
/**************************************************************************
 * Mode.php, MemoryGame
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

/**
 * Mode entity
 * 
 * @ORM\Table(name="game_mode")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\ModeRepository")
 * 
 */
class Mode{
	
	/**
	 * Mode Unique ID.
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="smallint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var smallint
	 */
	private $id;
	
	/**
	 * Mode Label
	 * 
	 * @ORM\Column(type="string", length=150, nullable=false)
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner un libellé."
	 * )
	 * @Assert\Length(
	 *      min = "4",
	 *      max = "150",
	 *      minMessage = "Le libellé doit faire au moins {{ limit }} caractères.",
	 *      maxMessage = "Le libellé ne peut pas être plus long que {{ limit }} caractères."
	 * )
	 * @var string
	 */
	private $label;
	
	
	/**
	 * Mode Number of players
	 * 
	 * @ORM\Column(type="smallint", nullable=false, columnDefinition="smallint unsigned not null")
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner le nombre de joueurs pour ce mode."
	 * )
	 * @Assert\Range(
	 *      min = 1,
	 *      max = 4,
	 *      minMessage = "Vous devez choisir au minimum {{ limit }} joueur.",
	 *      maxMessage = "Vous devez choisir au maximum {{ limit }} joueurs."
	 * )
	 * @var smallint
	 */
	private $nbMaxPlayers;
		
	/**
	 *
	 * Get ID
	 * 
	 * @return Unique ID
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get Label
	 * 
	 * @return The game mode label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Set Label
	 * 
	 * @param $label
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
	
	/**
	 * Get Number of players
	 * 
	 * @return The limit of players
	 */
	public function getNbMaxPlayers() {
		return $this->nbMaxPlayers;
	}
	
	/**
	 * Set Number of players
	 * 
	 * @param $nbMaxPlayers
	 */
	public function setNbMaxPlayers($nbMaxPlayers) {
		$this->nbMaxPlayers = $nbMaxPlayers;
		return $this;
	}
}
