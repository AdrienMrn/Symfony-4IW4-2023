<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrganisationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Organisation;
use App\Form\OrganisationType;

#[Route('/organisations', name: 'organisation_')]
class OrganisationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        $organisations = $organisationRepository->findAll();
        return $this->render('organisation/index.html.twig', [
            'organisations' => $organisations,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $organisation = new Organisation();
        return $this->save($organisation, $request, $manager);
    }

    #[Route('/show/{id}', name: 'show')]
    public function show(string $id, OrganisationRepository $repository): Response
    {
        $organisation = $repository->find($id);
        return $this->render('organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(string $id, OrganisationRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        $organisation = $repository->find($id);
        return $this->save($organisation, $request, $manager);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(string $id, OrganisationRepository $repository, EntityManagerInterface $manager): Response
    {
        $organisation = $repository->find($id);
        $manager->remove($organisation);
        $manager->flush();

        return $this->redirectToRoute('organisation_index');
    }

    // Test pull request
    private function save(Organisation $organisation, Request $request, EntityManagerInterface $manager): Response
    {
        $template = 'organisation/new.html.twig';
        $redirectRoute = 'organisation_index';
        $redirectParams = [];
        if (!empty($organisation->getId())) {
            $template = 'organisation/edit.html.twig';
            $redirectRoute = 'organisation_show';
            $redirectParams = ['id' => $organisation->getId()];
        }
        $form = $this->createForm(OrganisationType::class, $organisation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($organisation);
            $manager->flush();

            return $this->redirectToRoute($redirectRoute, $redirectParams);
        }

        return $this->render($template, [
            'form' => $form,
            'organisation' => $organisation,
        ]);
    }
}
