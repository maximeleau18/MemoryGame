<?php
/**************************************************************************
 * HumanPlayer.php, MemoryGame
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
 * HumanPlayer entity
 * @ORM\Table(name="human_player")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\ComputerPlayerRepository")
 */
class HumanPlayer extends Player
{
	/**
	 * HumanPlayer Login
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $login;
	
	/**
	 * HumanPlayer Password
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $password;
	
	/**
	 * HumanPlayer Birthdate
	 * @ORM\Column(name="birthdate", type="datetime")
	 * @var \DateTime
	 */
	protected $birthDate;
	
	/**
	 * HumanPlayer Created date
	 * @ORM\Column(name="created_at", type="datetime")
	 * @var \DateTime
	 */
	protected $createdAt;
	
	/**
	 * Set login
	 *
	 * @param string $login
	 * @return HumanPlayer
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	
		return $this;
	}
	
	/**
	 * Get login
	 *
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}
	
	/**
	 * Set password
	 *
	 * @param string $password
	 * @return HumanPlayer
	 */
	public function setPassword($password)
	{
		$this->login = $password;
	
		return $this;
	}
	
	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	/**
	 * Set birthdate
	 *
	 * @param \DateTime $birthdate
	 * @return HumanPlayer
	 */
	public function setBirthDate($birthDate)
	{
		$this->birthDate = $birthDate;
	
		return $this;
	}
	
	/**
	 * Get birthdate
	 *
	 * @return \DateTime
	 */
	public function getBirthDate()
	{
		return $this->birthDate;
	}
	
	/**
	 * Set createdAt
	 *
	 * @param \DateTime $createdAt
	 * @return HumanPlayer
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	
		return $this;
	}
	
	/**
	 * Get createdAt
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
    /**
     * @var integer
     */
    private $id;


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
