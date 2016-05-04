<?php

namespace SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SeriesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('creator')
            ->add('year', 'choice', array('choices' => range(Date('Y') + 1, date('Y')-30)))
            ->add('imageFile', 'file', array(
                     'data_class'   =>  null,
                     'required'   =>  false,
                ))
            ->add('imageName')
            ->add('synopsis', 'textarea')
            ->add('persons', CollectionType::class, array('entry_type' => PersonType::class, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false,))
            ->add('language','choice', array('choices'  => array('English' => 'English', 'Français' => 'Français')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SiteBundle\Entity\Series'
        ));
    }
}
