<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->json([
            'name' => 'Fake API',
            'description' => 'Basic API for testing purposes. All names, emails, addresses and "personal data" are fake.',
        ]);
    }
}
