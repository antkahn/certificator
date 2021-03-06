<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\QuizConfigurationType;
use AppBundle\Form\QuizType;
use AppBundle\Model\Answer;
use AppBundle\Model\Question;
use AppBundle\Model\Quiz;
use AppBundle\Model\QuizConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuizController extends Controller
{
    /**
     * @Route("/quiz", name="quiz_configure")
     */
    public function configureAction(Request $request)
    {
        $quizConfiguration = new QuizConfiguration();

        $form = $this->createForm(new QuizConfigurationType(), $quizConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('quiz_take',
                [
                    'id' => $quizConfiguration->getCategory()->getId(),
                    'questionAmount' => $quizConfiguration->getQuestionAmount() ?: 10,
                ]
            );
        }

        return $this->render(':quiz:configuration.html.twig', [ 'form' => $form->createView()]);
    }

    /**
     * @Route("/quiz/random", name="quiz_random")
     */
    public function randomAction(Request $request) {
        $session = $request->getSession();
        $questions = [];

        if ($session->get('questionIds') != null && $request->isMethod('POST')) {
            $questionIds = $session->get('questionIds');

            foreach ($questionIds as $id) {
                $questions[] = $this->getDoctrine()->getRepository('AppBundle:Question')->findOneBy(['id' => $id]);
            }
        }
        else {
            $questions = $this->getDoctrine()->getRepository('AppBundle:Question')->getRandomQuestions();
            $questionIds = [];
            foreach ($questions as $question) {
                $questionIds[] = $question->getId();
            }
            $session->set('questionIds', $questionIds);

        }

        return $this->takeQuiz($request, $questions);
    }

    /**
     * @Route("/quiz/categorie/{id}/questions/{questionAmount}", name="quiz_take")
     */
    public function takeAction(Request $request, Category $category, $questionAmount)
    {
        $session = $request->getSession();
        $questions = [];

        if ($session->get('questionIds') != null && $request->isMethod('POST')) {
            $questionIds = $session->get('questionIds');

            foreach ($questionIds as $id) {
                $questions[] = $this->getDoctrine()->getRepository('AppBundle:Question')->findOneBy(['id' => $id]);
            }
        }
        else {
            $questions = $this->getDoctrine()->getRepository('AppBundle:Question')->getRandomQuestionsByCategory($category, $questionAmount);
            $questionIds = [];
            foreach ($questions as $question) {
                $questionIds[] = $question->getId();
            }
            $session->set('questionIds', $questionIds);

        }

        return $this->takeQuiz($request, $questions);
    }

    /**
     * @param Request $request
     * @param \AppBundle\Entity\Question[] $questions
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function takeQuiz(Request $request, $questions) {
        $session = $request->getSession();

        $quizQuestions = array_map(function($question) {

            $answers = array_map(function($answer) {

                return new Answer($answer);
            }, $question->getAnswers()->toArray());

            return new Question($question, $answers);
        }, $questions);

        $quiz = new Quiz($quizQuestions);

        $form = $this->createForm(new QuizType(), $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->remove('questionIds');

            $em = $this->getDoctrine()->getEntityManager();
            $answerRepository = $em->getRepository('AppBundle:Answer');

            $answersGiven = $quiz->getAnswers();

            $quizEntity = new \AppBundle\Entity\Quiz($this->getUser());

            foreach ($answersGiven as $answers) {
                foreach ($answers as $answer) {
                    $quizEntity->addAnswer($answerRepository->findOneBy(['id' => $answer->getId()]));
                }
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
    public function correctAction(\AppBundle\Entity\Quiz $quiz)
    {
        if ($this->getUser() !== $quiz->getUser()) {
            return $this->createNotFoundException('Quiz not found');
        }

        $questions = [];

        /** @var \AppBundle\Entity\Answer $answer */
        foreach ($quiz->getAnswers() as $answer) {
            $question = $answer->getQuestion();

            if (array_key_exists($question->getId(), $questions)) {
                $questions[$question->getId()]['givenAnswers'][] = $answer;
            }
            else {
                $questions[$question->getId()] = [
                    'rightAnswers' => array_filter($question->getAnswers()->toArray(), function($answer) {
                        return $answer->isTrue();
                    }),
                    'givenAnswers' => [ $answer ],
                    'statement' => $question->getStatement(),
                ];
            }
        }

        foreach ($questions as $id => $question) {
            $question['rightButNotGivenAnswers'] = array_diff(
                $question['rightAnswers'],
                $question['givenAnswers']
            );
            $questions[$id] = $question;
        }

        return $this->render(':quiz:correct.html.twig',
            [
                'questions' => $questions,
                'quiz' => $quiz,
            ]
        );
    }
}
