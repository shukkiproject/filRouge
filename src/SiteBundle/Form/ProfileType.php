<?php

namespace SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', 'file', array(
                    'data_class' => null,
                    ))
        ;
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
// For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}