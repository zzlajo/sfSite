<?php

namespace Abroad\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;


class RegistrationFormType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    //parent::buildForm($builder, $options);

    $builder->add('name')
            ->add('currentLocation')
            ->add('bornLocation')
        ;
    }
    
   public function getParent() {
       return 'fos_user_registration';
   }
    
//    /**
//     * @param OptionsResolverInterface $resolver
//     */
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Abroad\UserBundle\Entity\User'
//        ));
//    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'abroad_user_registration';
    }
}
