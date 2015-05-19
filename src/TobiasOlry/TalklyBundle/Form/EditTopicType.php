<?php

namespace TobiasOlry\TalklyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class EditTopicType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description', 'textarea', ['attr' => ['rows' => 8]])
            ->add('save', 'submit', ['label' => 'update', 'attr' => ['class' => 'button radius tiny right']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'TobiasOlry\TalklyBundle\Entity\Topic']);
    }

    public function getName()
    {
        return 'edit_topic';
    }
}
