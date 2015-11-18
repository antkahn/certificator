<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends Controller
{
    /**
     * @Route("/categorie/{id}/question/ajouter", name="question_add")
     */
    public function addAction(Request $request, Category $category)
    {
        $question = new Question();
        $category->addQuestion($question);

        $form = $this->createForm(new QuestionType(), $question);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($question);
            $em->flush();

            return $this->redirect($this->generateUrl('category_show', ['id' => $category->getId()]));
        }

        return $this->render(':question:add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/question/{id}/modifier", name="question_edit")
     */
    public function editAction(Request $request, Question $question)
    {
        $category = $question->getCategory();

        $form = $this->createForm(new QuestionType(), $question);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getEntityManager();

            $em->flush();

            return $this->redirect($this->generateUrl('category_show', ['id' => $category->getId()]));
        }

        return $this->render(':question:edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/question/{id}/voir", name="question_show")
     */
    public function showAction(Question $question)
    {

        return $this->render(':question:show.html.twig', ['question' => $question]);
    }
}
