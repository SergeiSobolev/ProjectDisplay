<?php

namespace App\Command;

use App\Service\UserManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:create-admin')]
class CreateAdminCommand extends Command
{
    protected static $defaultDescription = 'Добавление нового админа.';

    public function __construct(private readonly UserManager $userManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $io->ask('Enter email');
        $password = $io->ask('Enter password');
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Внесены данные об админе:',
            '============',
            '',
        ]);

        $output->writeln('email: ' . $email);
        $output->writeln('password: ' . $password);

        $this->userManager->create($email, $password);

        $output->writeln('Админ успешно создан!');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('Эта команда добавляет нового админа...');
    }
}