<?php

namespace App\Form\Gestapp;


use App\Entity\Gestapp\choice\Denomination;
use App\Entity\Gestapp\choice\OtherOption;
use App\Entity\Gestapp\choice\PropertyEnergy;
use App\Entity\Gestapp\choice\PropertyEquipement;
use App\Entity\Gestapp\choice\PropertyOrientation;
use App\Entity\Gestapp\choice\PropertyState;
use App\Entity\Gestapp\choice\PropertyTypology;
use App\Entity\Gestapp\Complement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComplementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('banner', ChoiceType::class, [
                'label' => 'Bannière sur vignette',
                'choices'  => [
                    'Coup de coeur' => "coup-de-coeur",
                    'A saisir' => 'a-saisir',
                    'Dernière entrée' => 'derniere-entree'
                ],
                'choice_attr' => [
                    'Coup de coeur' => ['data-data' => 'Coup de coeur'],
                    'A saisir' => ['data-data' => 'A saisir'],
                    'Dernière entrée' => ['data-data' => 'Dernière entrée'],
                ],
            ])
            ->add('disponibility', ChoiceType::class,[
                'label' => 'Disponibilité',
                'choices'  => [
                    'A définir' => "a-definir",
                    'Immédiatement' => 'immediatement',
                    'A partir de' => 'a-partir-de'
                ],
                'choice_attr' => [
                    'A définir' => ['data-data' => 'A définir'],
                    'Immédiatement' => ['data-data' => 'Immédiatement'],
                    'A partir de' => ['data-data' => 'A partir de'],
                ],
            ])
            ->add('location', ChoiceType::class, [
                'label' => 'Location',
                'choices'  => [
                    'A définir' => "a-definir",
                    'Immédiatement' => 'immediatement',
                    'A partir de' => 'a-partir-de'
                ],
                'choice_attr' => [
                    'A définir' => ['data-data' => 'A définir'],
                    'Immédiatement' => ['data-data' => 'Immédiatement'],
                    'A partir de' => ['data-data' => 'A partir de'],
                ],
            ])
            ->add('disponibilityAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => false,
                'by_reference' => true,
                'label' => 'A partir du'
                ])
            ->add('propertyTax', NumberType::class, [
                'label' => 'Charge de propriété',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('propertyOrientation', EntityType::class, [
                'class' => PropertyOrientation::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Orientation',
                'choice_attr' => function (PropertyOrientation $product, $key, $index) {
                    return ['data-data' => $product->getName() ];
                }
            ])
            ->add('propertyState', EntityType::class, [
                'class' => PropertyState::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Etat du bien',
                'choice_attr' => function (PropertyState $product, $key, $index) {
                    return ['data-data' => $product->getName() ];
                }
            ])
            ->add('level', IntegerType::class, [
                'label' => "Etage",
                'required' => false,
                'empty_data' => 0
            ])
            ->add('washroom', IntegerType::class, [
                'label' => "salle d'eau",
                'empty_data' => 0
            ])
            ->add('bathroom', IntegerType::class, [
                'label' => "salle de bain",
                'required' => false,
                'empty_data' => 0
            ])
            ->add('wc', IntegerType::class, [
                'label' => "wc",
                'required' => false,
                'empty_data' => 0
            ])
            ->add('terrace', IntegerType::class, [
                'label' => "Terasse",
                'required' => false,
                'empty_data' => 0
            ])
            ->add('balcony', IntegerType::class, [
                'label' => "Balcon",
                'required' => false,
                'empty_data' => 0
            ])
            ->add('isFurnished', ChoiceType::class, [
                'label' => 'Le bien est-il meublé ?',
                'choices' => [
                    'non' => 0,
                    'oui' => 1,
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('propertyEnergy', EntityType::class, [
                'class' => PropertyEnergy::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Energie',
                'choice_attr' => function (PropertyEnergy $product, $key, $index) {
                    return ['data-data' => $product->getName() ];
                }
            ])
            ->add('denomination', EntityType::class, [
                'label'=> 'Catégorie de bien',
                'class' => Denomination::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_attr' => function (Denomination $product, $key, $index) {
                    return ['data-data' => $product->getName() ];
                }
            ])
            ->add('propertyEquipment',EntityType::class, [
                'class' => PropertyEquipement::class,
                'label' => 'Equipement du bien',
                'multiple' => true,
                'choice_attr' => ChoiceList::attr($this, function (?PropertyEquipement $propertyEquipement) {
                    return $propertyEquipement ? ['data-data' => $propertyEquipement->getName()] : [];
                })
            ])
            ->add('propertyOtheroption',EntityType::class, [
                'label'=> 'Autres options de bien',
                'class' => OtherOption::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'choice_attr' => function (OtherOption $product, $key, $index) {
                    return ['data-data' => $product->getName() ];
                }
            ])
            ->add('propertyTypology', EntityType::class, [
                'class' => PropertyTypology::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_attr' => function (PropertyTypology $product, $key, $index) {
                    return ['data-data' => $product->getName() ];
                },
                'label' => 'Typologie du bien'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complement::class,
        ]);
    }
}
