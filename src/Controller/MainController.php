<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method BucketEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method BucketEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method BucketEntity[]    findAll()
 * @method BucketEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class MainController extends AbstractController
{

    #[Route('/', name: 'login')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }

    // Route pour la page d'accueil
    #[Route('/home', name: 'main_home')]
    public function home()
    {
        $phrases = [
            'monte sur la Tour Eiffel depuis une Montgolfière.',
            'découvre les pyramides d\'Égypte.',
            'fais un safari en Afrique.',
            'plonge dans la Grande Barrière de Corail.',
            'explore les ruines de Machu Picchu.'
        ];

        return $this->render('main/home.html.twig', [
            'phrases' => $phrases,
        ]);
    }

}
