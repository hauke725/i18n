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
        foreach ($actionRepo->findInstanceOfDuplicates() as $instance) {
            /** @var Action $dup */
            foreach ($actionRepo->findBy(['name' => $instance->getName()]) as $dup) {
                if ($dup->getId() === $instance->getId()) {
                    continue;
                }
                $io->note('merging ' . $dup->getId() . ' into ' .$instance->getId());
                TranslationOccurrence::mergeActions($dup, $instance);
                $em->remove($dup);
            }
            $em->flush();
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
