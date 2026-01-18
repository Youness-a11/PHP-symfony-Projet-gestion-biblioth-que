<?php

namespace App\Controller;

use App\Repository\PaymentRepository;
use App\Service\StripeService;
use App\Entity\Notification;
use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\Payment;
use DateTimeImmutable;
use App\Entity\Transaction;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin')]
    public function index(
        LoanRepository $loanRepository,
        BookRepository $bookRepository,
        UserRepository $userRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/dashboard.html.twig', [
            'loans' => $loanRepository->findBy(['status' => 'en_attente'], ['loanDate' => 'ASC']),
            'books' => $bookRepository->findAll(),
            'pendingLoans' => $loanRepository->count(['status' => 'en_attente']),
            'totalBooks' => $bookRepository->count([]),
            'totalUsers' => $userRepository->count([]),
            'activeLoans' => $loanRepository->count(['status' => 'valide']),
        ]);
    }

    #[Route('/admin/loan/{id}/approve', name: 'admin_loan_approve')]
    public function approveLoan(Loan $loan, Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = $loan->getBook();
        if ($book) {
            $book->setAvailable(false);
        }
        $loan->setStatus('pending_payment');

        // On récupère ou crée le paiement (méthode Upsert pour éviter les doublons)
        $payment = $loan->getPayment();
        if (!$payment) {
            $payment = new Payment();
            $payment->setLoan($loan);
            $payment->setUser($loan->getUser());
            $payment->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($payment);
        }

        $payment->setAmount(5);
        $payment->setStatus('pending');
        $entityManager->flush(); // Nécessaire pour avoir l'ID du paiement

        // Création de la notification avec le BON LIEN
        $notification = new Notification();
        $notification->setUser($loan->getUser());
        $notification->setMessage("Votre demande pour '" . ($book ? $book->getTitle() : 'le livre') . "' est approuvée. Merci de régler les 5Dh.");
        
        // C'est ici que l'URL 'payment_show' est générée correctement
        $notification->setLink($this->generateUrl('payment_show', ['id' => $payment->getId()]));
        
        $notification->setCreatedAt(new DateTimeImmutable());
        $notification->setIsRead(false);
        
        $entityManager->persist($notification);
        $entityManager->flush();

        $this->addFlash('success', 'Emprunt validé et notification envoyée !');

        return $this->redirect($request->headers->get('referer') ?: $this->generateUrl('book_index'));
    }

    #[Route('/loan/{id}/reject', name: 'admin_loan_reject')]
    public function rejectLoan(Loan $loan, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $loan->setStatus('refuse');

        $book = $loan->getBook();
        $book->setAvailable(true);
        $book->setReturnDate(null);

        $transaction = new Transaction();
        $transaction->setUser($loan->getUser());
        $transaction->setBook($book);
        $transaction->setActionType('refus_emprunt');
        $transaction->setStatus('refuse');
        $transaction->setCreatedAt(new \DateTime());

        $em->persist($transaction);
        $em->flush();

        $this->addFlash('warning', 'Emprunt refusé.');

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/books', name: 'admin_books')]
    public function books(BookRepository $bookRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/books.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/books/new', name: 'admin_book_new')]
    public function newBook(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setAvailable(true);
            
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès!');
            return $this->redirectToRoute('admin_books');
        }

        return $this->render('admin/book_form.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
            'edit_mode' => false, // ADD THIS LINE
        ]);
    }

    #[Route('/books/{id}/edit', name: 'admin_book_edit')]
    public function editBook(Request $request, Book $book, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Livre modifié avec succès!');
            return $this->redirectToRoute('admin_books');
        }

        return $this->render('admin/book_form.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
            'edit_mode' => true,
        ]);
    }

    #[Route('/books/{id}/delete', name: 'admin_book_delete', methods: ['POST'])]
    public function deleteBook(Request $request, Book $book, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $em->remove($book);
            $em->flush();

            $this->addFlash('success', 'Livre supprimé avec succès!');
        }

        return $this->redirectToRoute('admin_books');
    }

    #[Route('/loans', name: 'admin_loans')]
    public function loanHistory(LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/loans.html.twig', [
            'loans' => $loanRepository->findBy([], ['loanDate' => 'DESC']),
        ]);
    }
    #[Route('/payments', name: 'admin_payments')]
    public function payments(PaymentRepository $paymentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/payments.html.twig', [
            'payments' => $paymentRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }
}