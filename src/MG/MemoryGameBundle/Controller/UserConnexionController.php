<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserConnexionController extends Controller
{
	public function indexAction()
	{
		$content = $this->renderView('MGMemoryGameBundle:UserConnexion:connexion.html.twig');
		return new Response($content);
	}
}