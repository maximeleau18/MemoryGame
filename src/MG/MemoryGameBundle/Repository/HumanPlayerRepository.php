<?php

namespace MG\MemoryGameBundle\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use MG\MemoryGameBundle\Entity\HumanPlayer;

/**
 * HumanPlayerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HumanPlayerRepository extends EntityRepository implements UserProviderInterface
{
	/**
	 *
	 * @param HumanPlayer username $username
	 * @throws UsernameNotFoundException
	 * @return \Doctrine\ORM\mixed
	 */
	public function loadUserByUsername($username)
	{
		$q = $this
		->createQueryBuilder('u')
		->where('u.username = :username')
		->setParameter('username', $username)
		->getQuery();
	
		try {
			// La méthode Query::getSingleResult() lance une exception
			// s'il n'y a pas d'entrée correspondante aux critères
			$user = $q->getSingleResult();
		} catch (NoResultException $e) {
			throw new UsernameNotFoundException(sprintf('Impossible de trouver le joueur portant ce login : "%s".', $username), 0, $e);
		}
	
		return $user;
	}
	
	/**
	 *
	 * @param UserInterface $user
	 * @throws UnsupportedUserException
	 * @return HumanPlayer
	 */
	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(
					sprintf(
							'Instances of "%s" are not supported.',
							$class
					)
			);
		}
	
		// Return HumanPlayer identied by this id
		return $this->find($user->getId());
	}
	
	/**
	 *
	 * @param HumanPlayer $class
	 * @return boolean
	 */
	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}
}
