<?php

namespace App\Command;

use App\Entity\Action;
use App\Entity\TranslationOccurrence;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppMergeDuplicatesCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:merge-duplicates';

    protected function configure()
    {
        $this
            ->setDescription('Merges duplicates in the database. This should be run periodically')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $actionRepo = $em->getRepository(Action::class);
        $instances = $actionRepo->findInstanceOfDuplicates();
        $progress = $io->createProgressBar(\count($instances));
        foreach ($instances as $instance) {
            $progress->advance();
            foreach ($actionRepo->findBy(['name' => $instance->getName()]) as $dup) {
                if ($dup->getId() === $instance->getId()) {
                    continue;
                }
                TranslationOccurrence::mergeActions($dup, $instance);
                $em->remove($dup);
            }
            $em->flush();
        }

        $io->success('Everything merged!');
    }
}
