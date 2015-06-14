<?php
/**************************************************************************
 * ComputerPlayer.php, MemoryGame
 *
 * Maxime Léau Copyright 2015
 * Description :
 * Author(s) : Maxime Léau <maxime.leau@imie-rennes.fr>
 * Licence : All right reserved.
 * Last update : June 12, 2015
 *
 **************************************************************************/
namespace MG\MemoryGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ComputerPlayer entity
 * @ORM\Table(name="computer_player")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\ComputerPlayerRepository")
 */
class ComputerPlayer extends Player
{
	
    /**
     * @var integer
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
