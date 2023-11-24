<?php

namespace App\Controller\Back;

use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\OrganisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrganisationController extends AbstractController
{
    #[Route('/organisation', name: 'organisation_index', methods: 'GET')]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        $organisations = $organisationRepository->findAll();

        return $this->render('back/organisation/index.html.twig', [
            'organisations' => $organisations
        ]);
    }

    #[Route('/organisation/{id}', name: 'organisation_show', requirements: ['id' => '\d{1,3}'], methods: 'GET')]
    public function show(Organisation $organisation): Response
    {
        return $this->render('back/organisation/show.html.twig', [
            'organisation' => $organisation
        ]);
    }

    #[Route('/organisation/new', name: 'organisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($organisation);
            $manager->flush();

            $this->addFlash('success', 'Création de votre association réalisée!');

            return $this->redirectToRoute('back_organisation_index');
        }

        return $this->render('back/organisation/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/organisation/update/{id}', name: 'organisation_update', requirements: ['id' => '\d{1,3}'], methods: ['GET', 'POST'])]
    public function update(Organisation $organisation, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', "Modification de l'association {$organisation->getName()} réalisée!");

            return $this->redirectToRoute('back_organisation_update', [
                'id' => $organisation->getId()
            ]);
        }

        return $this->render('back/organisation/update.html.twig', [
            'form' => $form,
            'organisation' => $organisation
        ]);
    }

    #[Route('/organisation/delete/{id}/{token}', name: 'organisation_delete', requirements: ['id' => '\d{1,3}'], methods: 'GET')]
    public function delete(Organisation $organisation, string $token, EntityManagerInterface $manager)
    {
        if ($this->isCsrfTokenValid('delete' . $organisation->getId(), $token)) {
            $manager->remove($organisation);
            $manager->flush();

            $this->addFlash('success', "Suppression de l'association {$organisation->getName()} réalisée!");
        }

        return $this->redirectToRoute('back_organisation_index');
    }
}















