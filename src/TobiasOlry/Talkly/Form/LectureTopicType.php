<?php

namespace TobiasOlry\Talkly\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'TobiasOlry\Talkly\Entity\Topic'
        ]);
    }

    public function getName()
    {
        return 'archive_topic';
    }
}
