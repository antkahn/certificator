<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use AppBundle\Form\AnswerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnswerController extends Controller
{
    /**
     * @Route("/question/{id}/reponse/ajouter", name="answer_add")
     */
    public function addAction(Request $request, Question $question)
    {
        $answer = new Answer();
        $answer->setQuestion($question);

        $form = $this->createForm(new AnswerType(), $answer);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getEntityManager();

            $em->persist($answer);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', ['id' => $question->getId()]));
        }

        return $this->render(':answer:add.html.twig', ['form' => $form->createView(), 'answer' => $answer]);
    }

    /**
     * @Route("/reponse/{id}/modifer", name="answer_edit")
     */
    public function editAction(Request $request, Answer $answer)
    {
        $form = $this->createForm(new AnswerType(), $answer);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $em = $this->getDoctrine()->getEntityManager();

            $em->flush();

            return $this->redirect($this->generateUrl('question_show', ['id' => $answer->getQuestion()->getId()]));
        }

        return $this->render(':answer:edit.html.twig', ['form' => $form->createView(), 'answer' => $answer]);
    }
}
