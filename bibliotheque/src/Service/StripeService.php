<?php

namespace App\Service;

use App\Entity\Loan;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeService
{
    private string $stripePublicKey;
    private UrlGeneratorInterface $urlGenerator;
    private EntityManagerInterface $entityManager;
    private StripeClient $stripeClient;

    public function __construct(
        string $stripePublicKey,
        StripeClient $stripeClient,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeClient = $stripeClient;
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        
        if (isset($_ENV['STRIPE_SECRET_KEY'])) {
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        }
    }

    /**
     * NOUVELLE MÉTHODE : Crée une session pour un paiement existant
     * C'est celle-ci qui va réparer l'erreur de l'image
     */
    public function createCheckoutSession(Payment $payment, string $successUrl): Session
    {
        return $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Frais d\'emprunt - ' . $payment->getLoan()->getBook()->getTitle(),
                    ],
                    'unit_amount' => $payment->getAmount() * 100, // Conversion en centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $this->urlGenerator->generate('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'customer_email' => $payment->getUser()->getEmail(),
            'metadata' => [
                'payment_id' => $payment->getId(),
            ],
        ]);
    }

    // --- TES MÉTHODES ORIGINALES (INTACTES) ---

    public function createPaymentSession(Loan $loan): Payment
    {
        if (empty($_ENV['STRIPE_SECRET_KEY']) || empty($_ENV['STRIPE_PUBLIC_KEY'])) {
            throw new \Exception('Stripe keys are not configured in .env.local');
        }

        $payment = new Payment();
        $payment->setUser($loan->getUser());
        $payment->setLoan($loan);
        $payment->setAmount(5.00); 
        $payment->setCurrency('eur');
        $payment->setStatus('pending');
        
        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        $checkoutSession = $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Emprunt: ' . $loan->getBook()->getTitle(),
                        'description' => 'Auteur: ' . $loan->getBook()->getAuthor(),
                    ],
                    'unit_amount' => 500, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->urlGenerator->generate('payment_success', ['payment_id' => $payment->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->urlGenerator->generate('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'customer_email' => $loan->getUser()->getEmail(),
        ]);

        $payment->setStripeSessionId($checkoutSession->id);
        $this->entityManager->flush();

        return $payment;
    }

    public function getPublicKey(): string
    {
        return $this->stripePublicKey;
    }

    public function verifyPayment(string $sessionId): bool
    {
        try {
            $session = $this->stripeClient->checkout->sessions->retrieve($sessionId);
            return $session->payment_status === 'paid';
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCheckoutSession(string $sessionId)
    {
        try {
            return $this->stripeClient->checkout->sessions->retrieve($sessionId);
        } catch (\Exception $e) {
            return null;
        }
    }
}