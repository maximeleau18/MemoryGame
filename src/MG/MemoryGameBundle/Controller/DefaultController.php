<?php

namespace MG\MemoryGameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MG\MemoryGameBundle\Entity\HumanPlayer;

class DefaultController extends Controller
{
    public function indexAction()
    {    	
    	return $this->render('MGMemoryGameBundle:Default:index.html.twig');
    }
}
