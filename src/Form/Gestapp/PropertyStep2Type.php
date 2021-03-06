<?php

namespace App\Form\Gestapp;

use App\Entity\Admin\Employed;
use App\Entity\Gestapp\Property;
use App\Form\SearchableEntityType;
use App\Repository\Admin\EmployedRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PropertyStep2Type extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $url){

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surfaceLand', IntegerType::class,[
                'label'=>'Surface de terrain',
                'empty_data' => 0,
                'required' => false
            ])
            ->add('surfaceHome', IntegerType::class,[
                'label'=>'Surface habitable',
                'empty_data' => 0,
                'required' => false
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix de vente',
                'required' => false
            ])
            ->add('honoraires', IntegerType::class, [
                'label' => 'honoraires',
                'required' => false
            ])

            ->add('priceFai', IntegerType::class, [
                'label' => 'Prix FAI',
                'required' => false
            ])
            ->add('dpeAt', DateType::class, [
                'label'=> 'Date du DPE',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required' => true,
                'by_reference' => true,
            ])
            ->add('diagDpe', IntegerType::class,[
                'label'=>'r??sultat DPE',
                'empty_data' => 0,
                'required' => false
            ])
            ->add('diagGpe', IntegerType::class, [
                'label'=>'r??sultat GPE',
                'empty_data' => 0,
                'required' => false
            ])
            ->add('cadasterZone',TextType::class, [
                'label' => 'Zone du cadastre',
                'required' => false
            ])
            ->add('cadasterNum', IntegerType::class, [
                'label' => 'immatriculation du cadastre',
                'required' => false
            ])
            ->add('cadasterSurface', IntegerType::class, [
                'label' => 'surface cadastrale',
                'required' => false
            ])
            ->add('eeaYear',DateType::class, [
                'label'=> 'Date de r??f??rence',
                'widget' => 'single_text',
                'format' => 'yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'by_reference' => true,
            ])
            ->add('dpeEstimateEnergyDown', IntegerType::class,[
                'label' => 'Basse',
                'empty_data' => 0,
                'required' => false
            ])
            ->add('dpeEstimateEnergyUp', IntegerType::class,[
                'label' => 'Elev??e',
                'empty_data' => 0,
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
