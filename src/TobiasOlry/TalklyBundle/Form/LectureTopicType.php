<?php

namespace TobiasOlry\TalklyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author David Badura <d.a.badura@gmail.com>
 */
class LectureTopicType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lectureDate', 'date', [
                'widget'   => 'single_text',
                'required' => false
            ])
            ->add('lectureNote', 'textarea', [
                'required' => false
            ])
            ->add('lectureHeld', 'checkbox', [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'TobiasOlry\TalklyBundle\Entity\Topic'
        ]);
    }

    public function getName()
    {
        return 'archive_topic';
    }
}
