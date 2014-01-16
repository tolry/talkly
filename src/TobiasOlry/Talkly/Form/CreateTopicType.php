<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateTopicType extends AbstractType
{

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('title', 'text')
                ->add('description', 'textarea')
            ;
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
                $resolver->setDefaults(array(
                    'data_class' => 'TobiasOlry\Talkly\Entity\Topic'
                ));
        }

        public function getName()
        {
                return 'topic';
        }

}

