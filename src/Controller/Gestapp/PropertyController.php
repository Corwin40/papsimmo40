<?php

namespace App\Controller\Gestapp;

use App\Entity\Gestapp\Complement;
use App\Entity\Gestapp\Property;
use App\Entity\Gestapp\Publication;
use App\Form\Gestapp\PropertyAvenantType;
use App\Form\Gestapp\PropertyImageType;
use App\Form\Gestapp\PropertyStep1Type;
use App\Form\Gestapp\PropertyStep2Type;
use App\Form\Gestapp\PropertyType;
use App\Repository\Admin\EmployedRepository;
use App\Repository\Gestapp\ComplementRepository;
use App\Repository\Gestapp\PropertyRepository;
use App\Repository\Gestapp\PublicationRepository;
use App\Repository\Gestapp\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestapp/property')]
class PropertyController extends AbstractController
{
    #[Route('/', name: 'op_gestapp_property_index', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $user = $this->getUser();
        if($hasAccess == true){
            //dd($propertyRepository->listAllProperties());
            return $this->render('gestapp/property/index.html.twig', [
                'properties' => $propertyRepository->listAllProperties(),
                'user' => $user
            ]);
        }else{
            //dd($propertyRepository->findBy(['refEmployed' => $user->getId()]));
            return $this->render('gestapp/property/index.html.twig', [
                'properties' => $propertyRepository->listPropertiesByemployed($user),
                'user' => $user
            ]);
        }

    }

    #[Route('/inCreating', name: 'op_gestapp_property_inCreating', methods: ['GET'])]
    public function inCreating(PropertyRepository $propertyRepository): Response
    {
        return $this->render('gestapp/property/increating.html.twig', [
            'properties' => $propertyRepository->findBy(array('isIncreating' => 1)),
        ]);
    }

    #[Route('/new', name: 'op_gestapp_property_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PropertyRepository $propertyRepository): Response
    {
        $user = $this->getUser()->getId();

        $property = new Property();
        $property->setRefEmployed($user);
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->redirectToRoute('op_gestapp_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/property/new.html.twig', [
            'property' => $property,
            'form' => $form,
        ]);
    }

    #[Route('/add', name:'op_gestapp_property_add', methods: ['GET', 'POST'])]
    public function add(
        PropertyRepository $propertyRepository,
        EmployedRepository $employedRepository,
        ComplementRepository $complementRepository,
        PublicationRepository $publicationRepository)
    {
        // Récupération du collaborateur
        $user = $this->getUser()->getId();
        $employed = $employedRepository->find($user);

        $complement = new Complement();
        $complement->setTerrace(0);
        $complement->setWashroom(0);
        $complement->setBathroom(0);
        $complement->setWc(0);
        $complement->setBalcony(0);
        $complement->setPropertyTax(0);
        $complement->setLevel(0);
        $complementRepository->add($complement);
        // création d'une fiche Publication
        $publication = new Publication();
        $publicationRepository->add($publication);

        // ---
        // Contruction de la référence pour chaque propriété
        // ---
        $date = new \DateTime();
        $lastproperty = $propertyRepository->findOneBy([], ['id'=>'desc']);           // Récupération de la dernière propriété enregistrée
        $refNumDate = $date->format('Y').'/'.$date->format('m');        // contruction de la première partie de référence

        // Création de l'entité Property
        $property = new Property();
        $property->setPiece(0);
        $property->setRoom(0);
        $property->setName('Nouveau bien');
        if(!$lastproperty){
            $lastRefNum = 1;
            $property->setRefnumdate($refNumDate);
            $property->setReflastnumber($lastRefNum);
        }else{
            $lastRefDate = $lastproperty->getRefnumdate();
            if($lastRefDate == $refNumDate){
                $lastRefNum = $lastproperty->getReflastnumber()+1;
                $property->setRefnumdate($refNumDate);
                $property->setReflastnumber($lastRefNum);
            }else{
                $lastRefNum = 1;
                $property->setRefnumdate($refNumDate);
                $property->setReflastnumber($lastRefNum);
            }
        }
        $property->setRef($refNumDate.'-'.$lastRefNum);
        $property->setSurfaceHome(0);
        $property->setSurfaceLand(0);
        $property->setPrice(0);
        $property->setHonoraires(0);
        $property->setPriceFai(0);
        $property->setDiagDpe(0);
        $property->setDiagGpe(0);
        $property->setDpeEstimateEnergyUp(0);
        $property->setDpeEstimateEnergyDown(0);
        $property->setCadasterSurface(0);
        $property->setCadasterNum(0);
        $property->setRefEmployed($employed);
        $property->setOptions($complement);
        $property->setPublication($publication);
        $property->setIsIncreating(1);
        $property->setRefMandat('');
        $propertyRepository->add($property);

        return $this->redirectToRoute('op_gestapp_property_show', [
            'id' => $property->getId()
        ]);
    }

    #[Route('/property/editimage/{id}', name: 'op_gestapp_property_editimage', methods: ['POST','GET'])]
    public function editImage(Property $property, Request $request, PropertyRepository $propertyRepository)
    {
        $form = $this->createForm(PropertyImageType::class, $property, [
            'action' => $this->generateUrl('op_gestapp_property_editimage', ['id'=>$property->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->redirectToRoute('op_gestapp_property_firstedit', ['id'=>$property->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/property/editimage.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'op_gestapp_property_show', methods: ['GET'])]
    public function show(Property $property): Response
    {
        $complement = $property->getOptions();

        return $this->render('gestapp/property/show.html.twig', [
            'property' => $property,
            'complement' => $complement->getId(),
            'publication' => $property->getPublication(),
        ]);
    }

    #[Route('/{id}/edit', name: 'op_gestapp_property_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Property $property, PropertyRepository $propertyRepository): Response
    {
        $complement = $property->getOptions();
        //dd($complement->getId());

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->redirectToRoute('op_gestapp_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/property/edit.html.twig', [
            'property' => $property,
            'idProperty' => $property->getId(),
            'complement' => $complement->getId(),
            'publication' => $property->getPublication(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/firstedit', name: 'op_gestapp_property_firstedit', methods: ['GET', 'POST'])]
    public function firstedit(Request $request, Property $property, PropertyRepository $propertyRepository): Response
    {

        $complement = $property->getOptions();
        //dd($complement->getId());

        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->redirectToRoute('op_gestapp_property_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestapp/property/edit.html.twig', [
            'property' => $property,
            'idProperty' => $property->getId(),
            'complement' => $complement->getId(),
            'publication' => $property->getPublication(),
            'form' => $form,
        ]);
    }


    #[Route('/firststep/{id}', name: 'op_gestapp_property_firststep', methods: ['GET', 'POST'])]
    public function firstStep(Request $request, Property $property, PropertyRepository $propertyRepository)
    {
        //dd($property);
        $form = $this->createForm(PropertyStep1Type::class, $property, [
            'action' => $this->generateUrl('op_gestapp_property_firststep', ['id'=>$property->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        //dd($request->getContent());

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->json([
                'code'=> 200,
                'message' => "Les informations du bien ont été correctement ajoutées."
            ], 200);

        }
        //dd($form->getErrors()->count());
        return $this->renderform('gestapp/property/Step/firststep.html.twig',[
            'form'=>$form,
            'property'=>$property,
        ]);
    }

    #[Route('/secondstep/{id}', name: 'op_gestapp_property_secondstep', methods: ['GET', 'POST'])]
    public function secondStep(Request $request, Property $property, PropertyRepository $propertyRepository)
    {
        //dd($property);
        $form = $this->createForm(PropertyStep2Type::class, $property, [
            'action' => $this->generateUrl('op_gestapp_property_secondstep',['id'=>$property->getId()]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        //dd($request->getContent());

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->json([
                'code'=> 200,
                'message' => "Les informations du bien ont été correctement ajoutées."
            ], 200);

        }
        return $this->renderform('gestapp/property/Step/secondstep.html.twig',[
            'form'=>$form,
            'property'=>$property
        ]);
    }

    #[Route('/stepinformationsimg/{id}', name: 'op_gestapp_property_stepinformationsimg', methods: ['GET', 'POST'])]
    public function stepInformationsImag(Request $request, Property $property, PropertyRepository $propertyRepository)
    {

        //dd($request->files->get('file'));
        $property->setImageFile($request->files->get('file'));
        $propertyRepository->add($property);
        //dd($property);

        return $this->json([
            'code'=> 200,
            'message' => "Les informations du bien ont été correctement ajoutées."
        ], 200);
    }

    #[Route('/{id}', name: 'op_gestapp_property_delete', methods: ['POST'])]
    public function delete(Request $request, Property $property, PropertyRepository $propertyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {
            $propertyRepository->remove($property);
        }

        return $this->redirectToRoute('op_gestapp_property_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/increatingdel/{id}', name:'op_gestapp_property_increatingdel', methods: ['POST'] )]
    public function increatingDel(Property $property, PropertyRepository $propertyRepository)
    {
        $propertyRepository->remove($property);

        $properties = $propertyRepository->findBy(array('isIncreating' => 1));

        return $this->json([
            'code'=> 200,
            'message' => "Les informations du bien ont été correctement ajoutées.",
            'liste' => $this->renderView('gestapp/property/_increating.html.twig', [
                'properties' => $properties
                ])
        ], 200);
    }

    #[Route('/del/{id}', name:'op_gestapp_property_del', methods: ['POST'] )]
    public function Del(Property $property, PropertyRepository $propertyRepository, PhotoRepository $photoRepository)
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $user = $this->getUser();

        $photos = $photoRepository->findby(['property' => $property]);
        foreach($photos as $photo){
            $photoRepository->remove($photo);
        }
        $propertyRepository->remove($property);

        if($hasAccess == true){
            $properties = $propertyRepository->listAllProperties();
        }else{
            $properties = $propertyRepository->listPropertiesByemployed($user);
        }

        return $this->json([
            'code'=> 200,
            'message' => "Les informations du bien ont été correctement ajoutées.",
            'liste' => $this->renderView('gestapp/property/_list.html.twig', [
                'properties' => $properties
            ])
        ], 200);
    }

    #[Route('/lastproperty', name: 'op_gestapp_properties_lastproperty', methods: ['GET'])]
    public function LastProperty(PropertyRepository $propertyRepository)
    {
        $properties = $propertyRepository->fivelastproperties();

        return $this->renderForm('webapp/page/property/lastproperties.html.twig', [
            'properties' => $properties,
        ]);

    }

    #[Route('/oneproperty/{id}', name: 'op_gestapp_properties_oneproperty', methods: ['GET'])]
    public function OneProperty(Property $property, PropertyRepository $propertyRepository)
    {
        $oneproperty = $propertyRepository->oneProperty($property->getId());
        $options = $property->getOptions();
        $equipments = $options->getPropertyEquipment();
        //dd($equipment);

        return $this->render('webapp/page/property/oneproperty.html.twig', [
            'property' => $oneproperty,
            'equipments' => $equipments
        ]);
    }

    #[Route('/addAvenant/{id}', name: 'op_gestapp_properties_addavenant', methods: ['POST'])]
    public function AddAvenant(Property $property, PropertyRepository $propertyRepository, Request $request)
    {
        $form = $this->createForm(PropertyAvenantType::class, $property, [
            'action' => $this->generateUrl('op_gestapp_properties_addavenant',['id'=>$property->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        //dd($form->isValid());

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyRepository->add($property);
            return $this->json([
                'code'=> 200,
                'message' => "Les informations du bien ont été correctement ajoutées."
            ], 200);

        }

        //dd($form->getErrors());

        return $this->renderForm('gestapp/property/Step/PriceAvenant.html.twig', [
            'property' => $property,
            'avenant' => $form
        ]);
    }

}
