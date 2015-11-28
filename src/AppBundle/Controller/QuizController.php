<?php

namespace AppBundle\Controller;

use AppBundle\Form\QuizType;
use AppBundle\Model\Answer;
use AppBundle\Model\Question;
use AppBundle\Model\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends Controller
{
    /**
     * @Route("/quiz", name="quiz_take")
     */
    public function takeAction(Request $request)
    {
        $qs = $this->getDoctrine()->getRepository('AppBundle:Question')->getRandomQuestions(10);

        $questions = [];

        foreach ($qs as $q) {
            $question = new Question($q);

            $answers = [];

            foreach ($q->getAnswers() as $a) {
                $answers[] = new Answer($a);
            }

            $question->setAnswers($answers);

            $questions[] = $question;
        }

        $quiz = new Quiz($questions);

        $form = $this->createForm(new QuizType(), $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getEntityManager();
            $answerRepository = $em->getRepository('AppBundle:Answer');

            $answersGiven = $quiz->getAnswers();

            $quizEntity = new \AppBundle\Entity\Quiz();

            foreach ($answersGiven as $ans) {
                $quizEntity->addAnswer($answerRepository->findOneBy(['id' => $ans->getId()]));
            }

            $em->persist($quizEntity);
            $em->flush();

            return $this->redirectToRoute('quiz_correct', ['id' => $quizEntity->getId()]);
        }

        return $this->render(':quiz:take.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/quiz/{id}/correction", name="quiz_correct")
     */
    public function correctAction(Request $request, \AppBundle\Entity\Quiz $quiz)
    {
        return $this->render(':quiz:correct.html.twig', ['quiz' => $quiz]);
    }
}
