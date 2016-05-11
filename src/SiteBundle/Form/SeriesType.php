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
        $array = [];
        $assoc = [];

        for ($i=1; $i > -29 ; $i--) { 
            $array[]=Date('Y') + $i;
        }
        foreach ($array as $i => $value) {
            $assoc[$value] = $value;
        }

        $builder
            ->add('name')
            ->add('creator')
            ->add('year', 'choice', array('choices' => $assoc))
            ->add('imageFile', 'file', array(
                     'data_class'   =>  null,
                     'required'   =>  false,
                ))
            ->add('synopsis', 'textarea')
            ->add('persons', CollectionType::class, array('entry_type' => PersonType::class, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false,))
            ->add('language','choice', array('choices'  => array('en' => 'English', 'fr' => 'Français')))
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
