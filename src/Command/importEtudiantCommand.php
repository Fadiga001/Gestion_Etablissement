<?php 

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'importcsv')]
class importEtudiantCommand extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dd('importer la liste étudiants');
    }
}