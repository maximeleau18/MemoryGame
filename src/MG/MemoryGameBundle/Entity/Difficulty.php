<?php
/**************************************************************************
 * Difficulty.php, MemoryGame
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
 * Difficulty entity
 * 
 * @ORM\Table(name="game_difficulty")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\DifficultyRepository")
 * 
 */
class Difficulty{
	
	/**
	 * Difficulty Unique ID.
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="smallint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	private $id;
	
	/**
	 * Difficulty label
	 * 
	 * @ORM\Column(type="string", length=150, nullable=false)
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner un libellé."
	 * )
	 * @Assert\Length(
     *      min = "3",
     *      max = "150",
     *      minMessage = "Le libellé doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Le libellé ne peut pas être plus long que {{ limit }} caractères."
     * )
	 * @var string
	 */
	private $label;
	
	/**
	 * Difficulty Number couple cards
	 * 
	 * @ORM\Column(type="smallint", nullable=false, columnDefinition="smallint unsigned")
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner le nombre de paires de cartes."
	 * )
     * @Assert\Range(
     *      min = 2,
     *      max = 18,
     *      minMessage = "Vous devez choisir au minimum {{ limit }} paires de cartes.",
     *      maxMessage = "Vous devez choisir au maximum {{ limit }} paires de cartes."
     * )
	 * @var smallint
	 */
	private $nbCoupleCards;
	
	/**
	 * Difficulty Timer
	 * 
	 * @ORM\Column(type="float", nullable=false, columnDefinition="float unsigned")
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner un minuteur (en secondes)."
	 * )
     * @Assert\Range(
     *      min = 30.0,
     *      max = 600.0,
     *      minMessage = "Vous devez choisir au minimum {{ limit }} secondes pour le minuteur.",
     *      maxMessage = "Vous devez choisir au maximum {{ limit }} secondes pour le minuteur."
     * )
	 * @var float
	 */
	private $timer;
	
	/**
	 * Difficulty Games
	 * 
	 * @ORM\OneToMany(targetEntity="MG\MemoryGameBundle\Entity\Game", mappedBy="difficulty")
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $games;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->games = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * Get Difficulty ID
	 * 
	 * @return Unique ID
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get Difficulty label
	 *
	 * @return The difficulty label
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Set Difficulty label
	 *
	 * @param $label
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
	
	/**
	 * Get Difficulty number couple cards
	 *
	 * @return The number of couple of cards
	 */
	public function getNbCoupleCards() {
		return $this->nbCoupleCards;
	}
	
	/**
	 * Set Difficulty number couple cards
	 * 
	 * @param $nbCoupleCards
	 */
	public function setNbCoupleCards($nbCoupleCards) {
		$this->nbCoupleCards = $nbCoupleCards;
		return $this;
	}
	
	/**
	 * Get Difficulty timer
	 *
	 * @return The timer
	 */
	public function getTimer() {
		return $this->timer;
	}
	
	/**
	 * Set Difficulty timer
	 * 
	 * @param $timer
	 */
	public function setTimer($timer) {
		$this->timer = $timer;
		return $this;
	}		

    /**
     * Add Games
     *
     * @param \MG\MemoryGameBundle\Entity\Game $games
     * @return \MG\MemoryGameBundle\Entity\Difficulty
     */
    public function addGame(\MG\MemoryGameBundle\Entity\Game $games)
    {
        $this->games[] = $games;
		$games->setDifficulty($this);
		
        return $this;
    }

    /**
     * Remove Games
     *
     * @param \MG\MemoryGameBundle\Entity\Game $games
     */
    public function removeGame(\MG\MemoryGameBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get Games
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }

}
