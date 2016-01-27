<?php

namespace AppBundle\Command;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Category;
use AppBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Parser;

class ImportSymfonyPackQuestionsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('pack:questions:import')
            ->setDescription('Import Symfony pack questions into the database')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure you want to import symfony pack questions into the database ?', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $output->writeln('<info>=== Importing Symfony Pack Questions ===</info>');

        $yaml = new Parser();

        $filesToParse = [
            'architecture',
            'automated-tests',
            'bundles',
            'cache-http',
            'command-line',
            'controllers',
            'dependency-injection',
            'forms',
            'http',
            'misc',
            'php',
            'routing',
            'security',
            'standardization',
            'symfony3',
            'templating',
            'validation',
        ];

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($filesToParse as $fileName) {

            $file = $yaml->parse(file_get_contents('app/Resources/symfony-pack-questions/' . $fileName . '.yml'));

            $category = new Category();
            $category->setName($file['category']);

            $em->persist($category);

            foreach ($file['questions'] as $question) {
                $questionEntity = new Question();
                $questionEntity->setCategory($category);
                $questionEntity->setStatement($question['question']);

                $em->persist($questionEntity);

                foreach ($question['answers'] as $answer) {
                    $answerEntity = new Answer();
                    $answerEntity->setStatement($answer['value']);
                    $answerEntity->setVeracity($answer['correct']);
                    $answerEntity->setQuestion($questionEntity);

                    $em->persist($answerEntity);
                }
            }
        }

        $em->flush();

        $output->writeln('<info>=== Import of Symfony Pack Questions Successful ===</info>');
    }
}
