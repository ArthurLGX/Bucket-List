<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BucketCreateType;
use App\Form\BucketUpdateType;
use App\Repository\CategoryEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BucketEntity;
use App\Repository\BucketEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bucketlist', name: 'app_bucket_')]
class BucketController extends AbstractController
{

    private BucketEntityRepository $bucketEntityRepository;
    private CategoryEntityRepository $categoryEntityRepository;

    public function __construct(BucketEntityRepository $bucketEntityRepository,
                                CategoryEntityRepository $categoryEntityRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->bucketEntityRepository = $bucketEntityRepository;
        $this->categoryEntityRepository = $categoryEntityRepository;

    }


    //On récupère tous les items de la BDD
    #[Route('/', name: 'list')]
    public function index(BucketEntityRepository $bucketEntityRepository, CategoryEntityRepository $categoryEntityRepository): Response
    {

        //On récupère les items dont l'user_id ou l'author_id est le même que l'id de l'user connecté
        $items = $bucketEntityRepository->findBy(['user' => $this->getUser()]);

        if (!$items) {
            //liste vide
            $item = [];
        }



        //on récupère toutes les catégories dans la bdd depuis la table category
        $categories = $categoryEntityRepository->findAll();
        return $this->render('/bucket/index.html.twig', [
            'items' => $items,
            'categories' => $categories
        ]);
    }

    #[Route('/', name: 'filter',  methods: ['GET'])]
    public function filter(Request $request, CategoryEntityRepository $categoryEntityRepository): Response
    {
        $categories = $categoryEntityRepository->findAll();
        $status = $request->query->get('status', 'all');
        $dateOrder = $request->query->get('date', 'asc');

        $queryBuilder = $this->bucketEntityRepository->createQueryBuilder('b');

        if ($status !== 'all') {
            $queryBuilder->andWhere('b.status = :status')
                ->setParameter('status', $status);
        }

        if ($dateOrder === 'asc') {
            $queryBuilder->orderBy('b.createdAt', 'ASC');
        } else {
            $queryBuilder->orderBy('b.createdAt', 'DESC');
        }

        $items = $queryBuilder->getQuery()->getResult();

        return $this->render('bucket/index.html.twig', [
            'items' => $items,
            'categories' => $categories
        ]);
    }
    // Method to handle the search functionality
    #[Route('/search', name: 'recherche', methods: ['POST'])]
    public function search(Request $request, CategoryEntityRepository $categoryEntityRepository): Response
    {
        $categories = $categoryEntityRepository->findAll();
        $searchTerm = $request->request->get('rechercher');

        $toggle = $request->request->get('toggle');

        //switch cases
        switch ($toggle) {
            case 'titre':
                $items = $this->bucketEntityRepository->findByTitle($searchTerm);
                break;
            case 'auteur':
                $items = $this->bucketEntityRepository->findByAuthor($searchTerm);
                break;
            default:
                $items = $this->bucketEntityRepository->findAll();
        }

        return $this->render('bucket/index.html.twig', [
            'items' => $items,
            'categories' => $categories
        ]);
    }

    #[Route('/searchByCategory', name: 'recherche_categorie', methods: ['POST'])]
    public function searchCategorie(Request $request, CategoryEntityRepository $categoryEntityRepository): Response
    {
        $categories = $categoryEntityRepository->findAll();
        $searchTerm = $request->request->get('category');

        //on convertit le string en int
        $searchTerm = (int)$searchTerm;
        //if the category is Toutes les catégories, we display all the items
        if ($searchTerm === 0) {
            $items = $this->bucketEntityRepository->findAll();
        } else {
            $items = $this->bucketEntityRepository->findByCategory($searchTerm);
        }

        return $this->render('bucket/index.html.twig', [
            'items' => $items,
            'categories' => $categories

        ]);
    }
    #[Route('/searchByCategory/{id}', name: 'recherche_categorie_by_id', requirements: ['id' => '\d+'])]
    public function searchCategorieById(int $id, CategoryEntityRepository $categoryEntityRepository): Response
    {

        //On récupère l'id de la catégorie
        $searchTerm = $id;
        //on convertit le string en int
        $searchTerm = (int)$searchTerm;
        //On cherche les items qui ont cette catégorie
        $items = $this->bucketEntityRepository->findByCategory($searchTerm);
        //On récupère toutes les catégories dans la bdd depuis la table category

        //on passes les items et les catégories à la vue
        return $this->render('bucket/find.html.twig', [
            'items' => $items,
        ]);
    }


    // Method to handle status and date filtering


    // Update Item Details
    #[Route('/details/{id}', name: 'item', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function details(int $id, Request $request, BucketEntityRepository $bucketEntityRepository, EntityManagerInterface $entityManager): Response
    {
        $item = $bucketEntityRepository->find($id);

        if (!$item) {
            //liste vide
            $item = [];
        }

        // Handle the update form submission
        if ($request->isMethod('POST')) {
            $status = $request->request->get('status');
            $item->setStatus($status);
            $entityManager->flush();
            $this->addFlash('success', 'Votre bucket a bien été mis à jour.');
            return $this->redirectToRoute('app_bucket_item', ['id' => $id]);
        }

        return $this->render('/bucket/details.html.twig', [
            'item' => $item
        ]);
    }

// Delete Item


    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST', 'DELETE'])]
    public function delete(int $id, BucketEntityRepository $bucketEntityRepository, EntityManagerInterface $entityManager): RedirectResponse
    {
        $item = $bucketEntityRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        //flash
        $this->addFlash('success', 'Votre bucket a bien été supprimé.');

        $entityManager->remove($item);
        $entityManager->flush();

        return $this->redirectToRoute('app_bucket');
    }

    #[Route('/create/', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $item = new BucketEntity();
        //On crée un formulaire
        $bucketForm = $this->createForm(BucketCreateType::class, $item);

        $bucketForm->handleRequest($request);

        if ($bucketForm->isSubmitted() && $bucketForm->isValid()) {

            //On controle que l'item n'existe pas déjà avec un findById
            $existingItem = $this->bucketEntityRepository->findOneBy(['name' => $item->getName()]);
            if ($existingItem) {
                $this->addFlash('warning', 'Un bucket avec ce titre existe déjà.');
                return $this->redirectToRoute('app_bucket_create');
            }

            $item = $bucketForm->getData();
            //On associe l'item au user connecté et on l'ajoute à author
            $item->setAuthor($this->getUser());
            $item->setUser($this->getUser());
            $item->setStatus('undone');
            $item->setIsPublished(true);
            $item->setCreatedAt(new \DateTime());
            $entityManager->persist($item);
            $entityManager->flush();
            $this -> addFlash('success', 'Votre bucket a bien été créé.');
            return $this->redirectToRoute('app_bucket_list');
        }

        return $this->render('/bucket/create.html.twig', [
            'bucketForm' => $bucketForm->createView()
        ]);
    }


    //Post
    #[Route('/find/', name: 'find')]
    public function find(BucketEntityRepository $bucketEntityRepository, CategoryEntityRepository $categoryEntityRepository, Request $request): Response
    {

        //On ne veut récupérer que les items dont l'user_id est différent de l'id de l'user connecté
        $user = $this->getUser();
        $categories = $categoryEntityRepository->findAll();
        $queryBuilder = $bucketEntityRepository->createQueryBuilder('b');
        $queryBuilder->where('b.user != :user')
            ->setParameter('user', $user);

        $items = $queryBuilder->getQuery()->getResult();
        return $this->render('bucket/index.html.twig', [
            'items' => $items,
            'categories' => $categories
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(int $id, Request $request, BucketEntityRepository $bucketEntityRepository, EntityManagerInterface $entityManager): Response
    {
        $item = $bucketEntityRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        $bucketForm = $this->createForm(BucketUpdateType::class, $item);

        $bucketForm->handleRequest($request);

        //On update uniquement le bucket dont l'id_user est le même que celui de l'user connecté
        if ($item->getUser() !== $this->getUser()) {
            $this->addFlash('warning', 'Vous ne pouvez pas modifier ce bucket.');
            return $this->redirectToRoute('app_bucket_list');
        }
        if ($bucketForm->isSubmitted() && $bucketForm->isValid()) {
            $entityManager->persist($item);
            $entityManager->flush();
            $this->addFlash('success', 'Votre bucket a bien été mis à jour.');
            return $this->redirectToRoute('app_bucket');
        }

        return $this->render('/bucket/update.html.twig', [
            'bucketForm' => $bucketForm->createView(),
            'item' => $item
        ]);
    }

#[Route('/ajoutListeUser', name: 'ajout_list_user', requirements: ['id' => '\d+'])]
    public function ajoutListeUser(int $id, Request $request, BucketEntityRepository $bucketEntityRepository, EntityManagerInterface $entityManager): Response
    {
        $item = $bucketEntityRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        $item->setUser($this->getUser());
        $entityManager->persist($item);
        $entityManager->flush();
        $this->addFlash('success', 'Votre bucket a bien été ajouté à votre liste.');
        return $this->redirectToRoute('app_bucket_list');
    }
}