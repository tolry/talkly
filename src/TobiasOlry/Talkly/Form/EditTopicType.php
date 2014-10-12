<?php

namespace TobiasOlry\Talkly\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'TobiasOlry\Talkly\Entity\Topic']);
    }

    public function getName()
    {
        return 'edit_topic';
    }
}
