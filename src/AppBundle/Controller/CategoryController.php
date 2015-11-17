<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/categorie/liste", name="category_list")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        return $this->render(':category:list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/categorie/ajouter", name="category_add")
     */
    public function addAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category_list'));
        }

        return $this->render(':category:add.html.twig', ['form' => $form->createView()]);
    }
}
