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
        /** @var \AppBundle\Entity\Question[] $randomQuestions */
        $randomQuestions = $this->getDoctrine()->getRepository('AppBundle:Question')->getRandomQuestions(10);

        $quizQuestions = array_map(function($question) {

            $answers = array_map(function($answer) {

                return new Answer($answer);
            }, $question->getAnswers()->toArray());

            return new Question($question, $answers);
        }, $randomQuestions);

        $quiz = new Quiz($quizQuestions);

        $form = $this->createForm(new QuizType(), $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getEntityManager();
            $answerRepository = $em->getRepository('AppBundle:Answer');

            $answersGiven = $quiz->getAnswers();

            $quizEntity = new \AppBundle\Entity\Quiz($this->getUser());

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
        if ($this->getUser() !== $quiz->getUser()) {
            return $this->createNotFoundException('Quiz not found');
        }

        return $this->render(':quiz:correct.html.twig',
            [
                'quiz' => $quiz,
                'goodAnswersCount' => $quiz->getGoodAnswersCount(),
                'badAnswersCount' => $quiz->getBadAnswersCount(),
            ]
        );
    }
}
