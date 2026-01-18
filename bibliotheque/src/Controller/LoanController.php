<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoanController extends AbstractController
{
    #[Route('/loan/{id}', name: 'app_loan')]
    public function loan(
        int $id,
        BookRepository $bookRepository,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $book = $bookRepository->find($id);

        if (!$book || !$book->isAvailable()) {
            $this->addFlash('error', 'Ce livre n\'est pas disponible.');
            return $this->redirectToRoute('book_index');
        }

        $loan = new Loan();
        $loan->setUser($this->getUser());
        $loan->setBook($book);
        $loan->setStatus('en_attente');
        $loan->setLoanDate(new \DateTime());

        // DO NOT set book as unavailable here - only after admin approval
        // $book->setAvailable(false); // REMOVE THIS LINE

        $em->persist($loan);
        $em->flush();

        $this->addFlash('success', 'Demande d\'emprunt envoyée! L\'admin doit l\'approuver.');
        return $this->redirectToRoute('book_index');
    }
}