<?php

namespace Primever\SecurityBundle\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Primever\SecurityBundle\Event\Events;
use Primever\SecurityBundle\Event\CommandUserCreateEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Command to create a new user in the system.
 */
#[AsCommand(
    name: "app:user:create",
    description: "Create a new user",
)]
class UserCreateCommand extends Command
{
    /**
     * Constructor for the UserCreateCommand class.
     */
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    /**
     * Configures the command to create a new user.
     */
    protected function configure(): void
    {
        $this
            ->addArgument("email", InputArgument::REQUIRED, "The email of the user.")
            ->addArgument("password", InputArgument::REQUIRED, "The password of the user.");
    }

    /**
     * Executes the command to create a new user.
     *
     * @param InputInterface $input An InputInterface instance, providing all the input needed for the command.
     * @return int Returns an exit code (0 for success, 1 for error).
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $password = $input->getArgument("password");
        $email = $input->getArgument("email");

        $user = new User();
        $user->setEmail($email);
        $hashPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashPassword);

        $this->userRepository->save($user,true);


        $io->success("User created successfully.");

        return Command::SUCCESS;
    }

}
