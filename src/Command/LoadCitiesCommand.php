<?php

namespace App\Command;

use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class LoadCitiesCommand extends Command
{
    private const DOWNLOAD_PATH_DOC = './src/Fixtures/cities.sql';

    protected static $defaultName = 'app:load-cities';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $rawQuery = [];

        $io = new SymfonyStyle($input, $output);

        $em = $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getEntityManager();


        $file = new \SplFileObject(self::DOWNLOAD_PATH_DOC);


        foreach($file as $line)
        {
            $progressBar->advance();
            $statement = $em->getConnection()->prepare($line);
            $statement->execute();
        }

        $progressBar->finish();

        $io->success('Cities have been loaded to database.');
    }
}
