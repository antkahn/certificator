<?php

namespace AppBundle\Form;

use AppBundle\Model\Answer;
use AppBundle\Model\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                /** @var Question $question */
                $question = $event->getData();
                $form = $event->getForm();

                $form->add('answers', 'choice', array(
                    'label' => $question->getStatement(),
                    'choices' => $question->getAnswers(),
                    'choices_as_values' => true,
                    'choice_label' => function($answer, $key, $index) {
                        /** @var Answer $answer */
                        return $answer->getStatement();
                    },
                    'expanded' => true,
                    'multiple' => true,
                ));
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Model\Question',
            'validation_groups' => ['Default'],
        ));
    }

    public function getName()
    {
        return 'answer_choice';
    }
}
