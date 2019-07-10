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
            $rawQuery[] = $line;
            $progressBar->advance();
        }

        $progressBar->finish();

        dd($rawQuery);
        $output->writeln([
            'Cities have been loaded to database.',
            '============',
            '',
        ]);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
