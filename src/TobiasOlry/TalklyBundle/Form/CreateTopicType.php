<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'TobiasOlry\TalklyBundle\Entity\Topic']);
    }

    public function getName()
    {
        return 'topic';
    }
}

