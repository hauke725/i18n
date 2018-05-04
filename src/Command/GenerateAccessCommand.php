<?php

namespace App\Command;

use App\Entity\ApiUser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateAccessCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:generate-access';

    protected function configure()
    {
        $this
            ->setDescription('Generate an API access')
            ->addArgument('user', InputArgument::OPTIONAL, 'Username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $user = $input->getArgument('user');

        while (!$user) {
            $user = $io->ask('Please enter a user name');
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        if ($em->getRepository(ApiUser::class)->findOneBy(['username' => $user])) {
            $io->error('User already exists.');
            return;
        }

        $apiUser = new ApiUser();
        $apiUser->setUsername($user);
        $apiUser->generateKey();

        $em->persist($apiUser);
        $em->flush();

        $io->success('You have created the user ' . $user . '. The key is: ' . $apiUser->getApiKey());
    }
}
