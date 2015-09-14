<?php

namespace Abroad\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;


class ProfileFormType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    //parent::buildForm($builder, $options);
    $builder->add('firstName')
            ->add('lastName')
            ->add('username')
//            ->add('plainPassword', 'repeated', array(
//            'first_name'  => 'password',
//            'second_name' => 'confirm',
//            'type'        => 'password',
//            ))
            ->add('email')
            ->add('currentLocation')
            ->add('bornLocation')
        ;
    }
    
//   public function getParent() {
//       return 'fos_user_registration';
//   }
    
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
        return 'abroad_user_profile';
    }
}
