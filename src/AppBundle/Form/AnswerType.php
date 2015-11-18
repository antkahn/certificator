<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statement', 'text', array(
                'label' => 'Intitulé de la réponse'
            ))
            ->add('veracity', 'choice', array(
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    true => 'Vrai',
                    false => 'Faux'
                ],
                'label' => 'Véracité',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Answer',
            'validation_groups' => ['Default'],
        ));
    }

    public function getName()
    {
        return 'answer';
    }
}
