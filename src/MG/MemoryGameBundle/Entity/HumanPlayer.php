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
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * HumanPlayer entity
 * 
 * @ORM\Table(name="human_player")
 * @ORM\Entity(repositoryClass="MG\MemoryGameBundle\Repository\HumanPlayerRepository")
 * @UniqueEntity(fields="username", message="Un joueur possède déjà ce pseudo.")
 */
class HumanPlayer extends Player implements UserInterface, \Serializable
{
	/**
	 * Human Player Unique ID
	 * 
	 * @var integer
	 */
	protected $id;
	
	/**
	 * HumanPlayer Username
	 * 
	 * @ORM\Column(type="string", length=50, nullable=false, unique=true)
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner un login"
	 * )
	 * @Assert\Length(
     *      min = "3",
     *      max = "50",
     *      minMessage = "Votre login doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Votre login ne peut pas être plus long que {{ limit }} caractères."
     * )
	 * @var string
	 */
	protected $username;
	
	/**
	 * HumanPlayer Encoders
	 * 
	 * @ORM\Column(type="string", length=32)
	 */
	protected $salt;
	
	/**
	 * HumanPlayer Password
	 * 
	 * @ORM\Column(type="string", length=255, nullable=false)
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner un mot de passe"
	 * )
	 * @Assert\Length(
     *      min = "8",
     *      max = "255",
     *      minMessage = "Votre mot de passe doit faire au moins {{ limit }} caractères.",
     *      maxMessage = "Votre mot de passe ne peut pas être plus long que {{ limit }} caractères."
     * )
     *  @var string
	 */
	protected $password;
	
	/**
	 * HumanPlayer Birthdate
	 * 
	 * @Assert\NotBlank(
	 * 		message = "Vous devez renseigner une date de naissance."
	 * )
	 * @ORM\Column(name="birthdate", type="datetime", nullable=false)
	 * @var \DateTime
	 */
	protected $birthDate;
	
	/**
	 * HumanPlayer Created date
	 * 
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 * @var \DateTime
	 */
	protected $createdAt;
	
	/**
	 * HumanPlayer RecordsBooks
	 *
	 * @ORM\OneToMany(targetEntity="MG\MemoryGameBundle\Entity\RecordsBook", mappedBy="player", cascade={"persist", "remove"})
	 * @var \Doctrine\Common\Collections\Collection
	 */
	private $recordsBooks;
		
	/**
	 * Default Constructor
	 */
	public function __construct()
	{
		$this->salt = md5(uniqid(null, true));
		$this->recordsBooks = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * Set Username
	 *
	 * @param string $username
	 * @return HumanPlayer
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	
		return $this;
	}
	
	/**
	 * Get Username
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	 * Set password
	 *
	 * @param string $password
	 * @return HumanPlayer
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getSalt()
    {
    	return $this->salt;
    }
    
    public function getRoles()
    {
    	return array('ROLE_USER');
    }
    
    public function eraseCredentials()
    {
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
    	return serialize(array(
    			$this->id,
    	));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
    	list (
    			$this->id,
    	) = unserialize($serialized);
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return HumanPlayer
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Add recordsBooks
     *
     * @param \MG\MemoryGameBundle\Entity\RecordsBook $recordsBooks
     * @return HumanPlayer
     */
    public function addRecordsBook(\MG\MemoryGameBundle\Entity\RecordsBook $recordsBook)
    {
        $this->recordsBooks[] = $recordsBook;
        $recordsBook->setPlayer($this);

        return $this;
    }

    /**
     * Remove recordsBooks
     *
     * @param \MG\MemoryGameBundle\Entity\RecordsBook $recordsBooks
     */
    public function removeRecordsBook(\MG\MemoryGameBundle\Entity\RecordsBook $recordsBooks)
    {
        $this->recordsBooks->removeElement($recordsBooks);
    }

    /**
     * Get recordsBooks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecordsBooks()
    {
        return $this->recordsBooks;
    }
}
