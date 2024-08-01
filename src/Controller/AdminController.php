<?php

namespace App\Controller;

use App\Repository\BucketEntityRepository;
use App\Repository\CategoryEntityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route(path: '/dashboard', name: 'dashboard')]
    public function dashboard(UserRepository $userRepository, CategoryEntityRepository $categoryEntityRepository): Response
    {
        //on récupère tous les utilisateurs
        $users = $userRepository->findAll();

        //on récupère toutes les catégories
        $categories = $categoryEntityRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'categories' => $categories
        ]);
    }

    /**
     * @throws ORMException
     */
    #[Route(path: '/delete_user/{id}', name: 'delete_user')]
    public function deleteUser(int $id, UserRepository $userRepository, BucketEntityRepository $bucketEntityRepository, EntityManagerInterface $entityManager): Response
    {
        // On récupère l'utilisateur
        $user = $userRepository->find($id);

        if ($user) {
            // On récupère les paniers de l'utilisateur
            $buckets = $bucketEntityRepository->findBy(['user' => $user]);

            // On supprime les paniers
            foreach ($buckets as $bucket) {
                $entityManager->remove($bucket);
            }

            // On supprime l'utilisateur
            $entityManager->remove($user);

            // On enregistre les modifications
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Utilisateur non trouvé.');
        }

        return $this->redirectToRoute('app_admin_dashboard');
    }
}
