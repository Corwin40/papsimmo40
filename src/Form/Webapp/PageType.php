<?php

namespace App\Form\Webapp;

use App\Entity\Webapp\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('description')
            ->add('isShowtitle')
            ->add('isShowdate')
            ->add('isMenu')
            ->add('state')
            ->add('metaKeywords')
            ->add('MetaDescrition')
            ->add('tag')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
