<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatusController extends Controller
{
    /**
     * @Route("/", name="status")
     */
    public function index()
    {
        return $this->json(['status' => 'OK!']);
    }
}
