<?php
/*
 *
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserProfileType extends AbstractType
{

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('email',               'email')
                ->add('name',                'text')
                ->add('notifyByEmail',       'checkbox', ['required' => false])
                ->add('notifyInApplication', 'checkbox', ['required' => false])
            ;
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
                $resolver->setDefaults([
                    'data_class' => 'TobiasOlry\Talkly\Entity\User'
                ]);
        }

        public function getName()
        {
                return 'user_profile';
        }

}


