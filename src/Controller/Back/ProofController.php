<?php

namespace App\Controller\Back;

use App\Entity\Proof;
use App\Form\ProofType;
use App\Repository\ProofRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/proof')]
class ProofController extends AbstractController
{
    #[Route('/', name: 'app_proof_index', methods: ['GET'])]
    public function index(ProofRepository $proofRepository): Response
    {
        if ($this->isGranted('ROLE_COORDINATOR')) {
            $proofs = $proofRepository->findAll();
        } else {
            $proofs = $proofRepository->findBy(['owner' => $this->getUser()]);
        }

        return $this->render('back/proof/index.html.twig', [
            'proofs' => $proofs,
        ]);
    }

    #[Route('/new', name: 'app_proof_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $proof = new Proof();
        $form = $this->createForm(ProofType::class, $proof);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($proof);
            $entityManager->flush();

            return $this->redirectToRoute('back_app_proof_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/proof/new.html.twig', [
            'proof' => $proof,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proof_show', methods: ['GET'])]
    #[Security('is_granted("ROLE_COORDINATOR") or proof.getOwner() === user')]
    public function show(Proof $proof): Response
    {
        return $this->render('back/proof/show.html.twig', [
            'proof' => $proof,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proof_edit', methods: ['GET', 'POST'])]
    //#[Security('is_granted("ROLE_COORDINATOR") or proof.getOwner() === user')]
    #[Security('is_granted("ROLE_COORDINATOR") or proof.getOwner() === user')]
    public function edit(Request $request, Proof $proof, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProofType::class, $proof);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('back_app_proof_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/proof/edit.html.twig', [
            'proof' => $proof,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proof_delete', methods: ['POST'])]
    //#[Security('is_granted("ROLE_COORDINATOR") or proof.getOwner() === user')]
    #[IsGranted('delete', 'proof')]
    public function delete(Request $request, Proof $proof, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proof->getId(), $request->request->get('_token'))) {
            $entityManager->remove($proof);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_app_proof_index', [], Response::HTTP_SEE_OTHER);
    }
}
