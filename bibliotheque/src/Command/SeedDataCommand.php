<?php

namespace App\Command;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:seed-data',
    description: 'Seed the database with sample data',
)]
class SeedDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Seeding database...');

        // Add books
        $books = [
            ['Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un classique de la littérature française'],
            ['1984', 'George Orwell', 'Un roman dystopique'],
            ['L\'Étranger', 'Albert Camus', 'Le récit de Meursault'],
            ['Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Le premier tome de la saga Harry Potter'],
            ['Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Une épopée fantasy'],
            ['Les Misérables', 'Victor Hugo', 'L\'histoire de Jean Valjean'],
            ['Da Vinci Code', 'Dan Brown', 'Un thriller'],
            ['Le Nom de la Rose', 'Umberto Eco', 'Un roman policier médieval'],
            ['Germinal', 'Émile Zola', 'Le récit de la vie des mineurs'],
            ['Orgueil et Préjugés', 'Jane Austen', 'Une comédie romantique'],
        ];

        foreach ($books as $bookData) {
            $book = new Book();
            $book->setTitle($bookData[0]);
            $book->setAuthor($bookData[1]);
            $book->setDescription($bookData[2]);
            $book->setAvailable(true);
            
            $this->entityManager->persist($book);
        }

        // Add sample users
        $users = [
            ['alice@emsi.ma', 'password123', ['ROLE_USER']],
            ['bob@emsi.ma', 'password123', ['ROLE_USER']],
            ['charlie@emsi.ma', 'password123', ['ROLE_USER']],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData[0]);
            $user->setRoles($userData[2]);
            
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $userData[1]
            );
            $user->setPassword($hashedPassword);
            
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
        
        $output->writeln('Database seeded successfully!');
        $output->writeln('10 books and 3 users added.');

        return Command::SUCCESS;
    }
}