<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:create-user', description: 'Créer un utilisateur')]
class CreateUserCommand extends Command
{
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Cette commande est un assisstant de création d\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
"               _____    ___       
              |_   _|__|   \ ___  
                | |/ _ \ |) / _ \ 
                |_|\___/___/\___/ 
            "
        ]);

        $helper = $this->getHelper('question');

        $question = new Question('Nom d\'utilisateur : ');
        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });
        $question->setValidator(function (string $value): string {
            if($this->validator->validate($value, [new NotBlank()])->count() > 0){
                throw new \RuntimeException('Nom d\'utilisateur invalide');
            }else{
                $this->usernameValid = true;
            }
            return $value;
        });
        $username = $helper->ask($input, $output, $question);


        $question = new Question('Adresse email : ');
        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });
        $question->setValidator(function (string $value): string {
            if($this->validator->validate($value, [new NotBlank(), new Email()])->count() > 0){
                throw new \RuntimeException('Adresse email invalide');
            }else{
                $emailValid = true;
            }
            return $value;
        });
        $email = $helper->ask($input, $output, $question);


        $passwordQuestion = new Question('Mot de passe : ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });
        $passwordQuestion->setValidator(function (string $value): string {
            if($this->validator->validate($value, [new NotBlank()])->count() > 0){
                throw new \RuntimeException('Le mot de passe ne peut pas être vide');
            }else{
                $passwordValid = true;
            }
            return $value;
        });
        $password = $helper->ask($input, $output, $passwordQuestion);

        $passwordQuestion = new Question('Répéter le mot de passe : ');
        $passwordQuestion->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });
        $passwordQuestion->setValidator(function (string $value) use ($password): string {
            if($value !== $password){
                throw new \RuntimeException('Les mots de passe ne correspondent pas');
            }
            return $value;
        });
        $passwordQuestion->setHidden(true);
        $repeatedPassword = $helper->ask($input, $output, $passwordQuestion);

        $output->writeln([
            "Nom d'utilisateur : $username",
            "Adresse email : $email",
            "Mot de passe : nice try :D"
        ]);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln([
            "Utilisateur créé avec succès !"
        ]);

        return Command::SUCCESS;
    }
}