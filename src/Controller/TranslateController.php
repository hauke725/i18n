<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslateController extends Controller
{
    /**
     * @Route("/translate", name="translate")
     */
    public function index()
    {
        return $this->render('translate.html.twig');
    }
}
