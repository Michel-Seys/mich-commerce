<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class CategoryController extends AbstractController
{
    /**
     * @Route ("/admin/category/{id}/edit", name="category_edit")
     * @param int $id
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function edit(int $id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            throw new NotFoundHttpException("Cette categorie n'existe pas !");
        }

//        $this->denyAccessUnlessGranted('CAN_EDIT', $category, "Vous n'êtes pas le créateur de cette catégorie (category)");

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/create", name="category_create")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
