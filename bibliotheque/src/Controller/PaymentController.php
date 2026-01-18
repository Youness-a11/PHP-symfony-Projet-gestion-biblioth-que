<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route('/{id}', name: 'payment_show', requirements: ['id' => '\d+'])]
    public function show(Payment $payment, StripeService $stripeService): Response
    {
        if ($payment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException(
                'Seul l\'utilisateur ' . $payment->getUser()->getEmail() . 
                ' peut accéder à cette page de paiement.'
            );
        }

        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
            'stripe_public_key' => $stripeService->getPublicKey(),
        ]);
    }

    #[Route('/{id}/pay', name: 'payment_pay', requirements: ['id' => '\d+'])]
    public function pay(Payment $payment, StripeService $stripeService, EntityManagerInterface $entityManager): Response
    {
        if ($payment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($payment->getStatus() !== 'pending') {
            $this->addFlash('warning', 'Ce paiement a déjà été traité.');
            return $this->redirectToRoute('payment_show', ['id' => $payment->getId()]);
        }

        // On crée la session Stripe UNE SEULE FOIS
        $checkoutSession = $stripeService->createCheckoutSession($payment, $this->generateUrl('payment_success', [
            'payment_id' => $payment->getId()
        ], UrlGeneratorInterface::ABSOLUTE_URL));

        // On sauvegarde l'ID de session pour le suivi
        $payment->setStripeSessionId($checkoutSession->id);
        $entityManager->flush();

        // On redirige vers l'URL sécurisée générée par Stripe
        return $this->redirect($checkoutSession->url);
    }

    #[Route('/success', name: 'payment_success')]
    public function success(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paymentId = $request->query->get('payment_id');
        
        if ($paymentId) {
            $payment = $entityManager->getRepository(Payment::class)->find($paymentId);
            if ($payment && $payment->getStatus() === 'pending') {
                $payment->setStatus('paid');
                if ($payment->getLoan()) {
                    $payment->getLoan()->setStatus('valide');
                }
                $entityManager->flush();
            }
        }
        
        $this->addFlash('success', 'Paiement réussi! Votre emprunt est confirmé.');
        return $this->redirectToRoute('book_index');
    }

    #[Route('/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        $this->addFlash('warning', 'Paiement annulé. Vous pouvez réessayer plus tard.');
        return $this->redirectToRoute('book_index');
    }

    #[Route('/{id}/cancel', name: 'payment_cancel_manual', requirements: ['id' => '\d+'])]
    public function cancelManual(Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($payment->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($payment->getStatus() === 'pending') {
            $payment->setStatus('cancelled');
            $entityManager->flush();
            $this->addFlash('warning', 'Paiement annulé.');
        }

        return $this->redirectToRoute('book_index');
    }
}