<?php

namespace SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class SeasonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('series')
            ->add('season', ChoiceType::class, array('choices' => range(0, 20)))
            ->add('episodes', CollectionType::class, array('entry_type' => EpisodeType::class, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false,));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SiteBundle\Entity\Season'
        ));
    }
}
