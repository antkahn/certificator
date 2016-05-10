<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', 'entity', [
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('questionAmount', 'integer', [
                'label' => 'Nombre de questions maximum',
                'data' => 10,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Model\QuizConfiguration',
            'validation_groups' => ['Default'],
        ));
    }

    public function getName()
    {
        return 'quiz_configuration';
    }
}
