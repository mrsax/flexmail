<?php

namespace App\Command;

use App\Entity\City;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use function json_last_error;
use function json_last_error_msg;


class LoadCitiesCommand extends Command
{
    private const DOWNLOAD_PATH_DOC = './src/Fixtures/cities.json';

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

        $io = new SymfonyStyle($input, $output);

        //start transaction
        $em = $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getEntityManager();
        $em->getConnection()->beginTransaction();

        $file = json_decode(file_get_contents(self::DOWNLOAD_PATH_DOC), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
        }

        try
        {

            foreach($file as $line)
            {
                $progressBar->advance();

                $city = new City();
                $city->setName($line['city'])
                    ->setZipcode($line['zip'])
                    ->setLongitude($line['lng'])
                    ->setLatitude($line['lat'])
                    ->setCreated();
                $em->persist($city);
            }
            $em->flush($city);
            $em->getConnection()->commit();

        } catch (Exception $e)
        {
            $em->getConnection()->rollBack();
            throw $e;
        }

        $progressBar->finish();

        $io->success('Cities have been loaded to database.');
    }
}
