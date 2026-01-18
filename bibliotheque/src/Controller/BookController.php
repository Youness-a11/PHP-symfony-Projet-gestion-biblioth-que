<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'book_index')]
    public function index(BookRepository $bookRepository, LoanRepository $loanRepository): Response
    {
        $books = $bookRepository->findAll();
        $pendingRequestsByBook = [];
        
        // Get all pending loans and group by book ID
        $pendingLoans = $loanRepository->findBy(['status' => 'en_attente']);
        foreach ($pendingLoans as $loan) {
            $bookId = $loan->getBook()->getId();
            if (!isset($pendingRequestsByBook[$bookId])) {
                $pendingRequestsByBook[$bookId] = 0;
            }
            $pendingRequestsByBook[$bookId]++;
        }

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'pendingRequestsByBook' => $pendingRequestsByBook,
        ]);
    }
}