<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use App\Repository\PaymentRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/mon-compte', name: 'user_dashboard')]
    public function dashboard(
        LoanRepository $loanRepository, 
        PaymentRepository $paymentRepository,
        NotificationRepository $notificationRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        
        return $this->render('user/dashboard.html.twig', [
            'loans' => $loanRepository->findBy(['user' => $user], ['loanDate' => 'DESC']),
            'payments' => $paymentRepository->findBy(['user' => $user], ['createdAt' => 'DESC']),
            'notifications' => $notificationRepository->findBy(
                ['user' => $user], 
                ['createdAt' => 'DESC'],
                10
            ),
            'user' => $user,
        ]);
    }

    #[Route('/mes-emprunts', name: 'user_loans')]
    public function loans(LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $loans = $loanRepository->findBy(
            ['user' => $this->getUser()],
            ['loanDate' => 'DESC']
        );

        return $this->render('user/loans.html.twig', [
            'loans' => $loans,
        ]);
    }

    #[Route('/mes-paiements', name: 'user_payments')]
    public function payments(PaymentRepository $paymentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $payments = $paymentRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('user/payments.html.twig', [
            'payments' => $payments,
        ]);
    }

    #[Route('/mon-compte/profil', name: 'user_profile')]
    public function profile(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}