<?php

namespace ToolsBundle\Command;

use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Validator\Constraints\Regex;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\Validation;
use ToolsBundle\Services\ExcelImporter\ExcelImporter;

class ToolsExcelImportCommand extends ContainerAwareCommand
{
    const EXCEL_IMPORT_WATCH_EVENT = 'excel_import_file';
    protected function configure()
    {
        $this
            ->setName('tools:excel:import')
            ->setDescription('Provide a way to import information from an Excel file')
            ->addArgument('manifest', InputArgument::REQUIRED, 'The excel manifest who defined what is imported')
            ->addArgument('input', InputArgument::REQUIRED, 'The name of the file where informations are being extracted and imported to database')
            ->addOption('timer', null, InputOption::VALUE_NONE, 'Print the computed time in millisecond')
            ->addOption('dump-sql', null, InputOption::VALUE_NONE, 'Dump The SQL with no insertion on the database')
            ->addUsage("tools:excel:export <manifest.yml> <input.xls>")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopWath = new Stopwatch();
        if($input->getOption('timer')) {
            $stopWath->start(self::EXCEL_IMPORT_WATCH_EVENT);
        }

        $system = $this->getContainer()->get('filesystem');
        $finder = new Finder();

        $manifestUri = $input->getArgument('manifest');
        $inputUri = $input->getArgument('input');


        $validator = Validation::createValidator();

        $manifestViolations = $validator->validate($manifestUri, [
            new Regex([
                'pattern' => "/.+\\.yml/",
                'match' => true,
                "message" => "Le Fichier Manifeste doit porter l'extension '.yml'"
            ])
        ]);

        if (0 !== count($manifestViolations)) {
            // there are errors, now you can show them
            foreach ($manifestViolations as $violation) {
                $output->writeln($violation->getMessage());
            }
            return;
        }

        $excelViolations = $validator->validate($inputUri, [
            new Regex([
                'pattern' => "/.+\\.xlsx/",
                'match' => true,
                "message" => "Le Fichier Input doit porter l'extension '.xlsx'"
            ])
        ]);

        if (0 !== count($excelViolations)) {
            // there are errors, now you can show them
            foreach ($excelViolations as $violation) {
                $output->writeln($violation->getMessage());
            }
            return;
        }

        if(!$system->exists($inputUri)) {
            $output->writeln("Le fichier '" . $inputUri . "' n'existe pas");
            return;
        }

        $finder->files()->in([__DIR__ . '/../Resources/ExcelManifests/'])->name($manifestUri);

        if($finder->count() === 0) {
            $output->writeln("The manifest file '" . $manifestUri. "' doesn't exist");
        }
        elseif ($finder->count() > 1) {
            $output->writeln("There is more than one file who match this name:");

            foreach ($finder as $fileInfo) {
                $output->writeln($fileInfo->getRealPath());
            }
        }
        else {
            $valid = true;
            $exporter = $this->getContainer()->get('tools.excel.importer');
            foreach ($finder as $fileInfo) {
                if(!$exporter->import($fileInfo->getRealPath(), $inputUri, $input->getOption('dump-sql'))) {
                    $exporter->getErrors()->forAll(function ($key, $error) use ($output) {
                        //                        Formatter Sample:
                        //                        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
                        //                        $output->getFormatter()->setStyle('fire', $style);
                        //                        $output->writeln('<fire>foo</fire>');

                        $output->writeln("<error>$error</error>", OutputInterface::VERBOSITY_VERBOSE);
                        return true;
                    });
                    $valid = false;
                }

            }

            if($input->getOption('timer'))
                $wathEvent = $stopWath->stop(self::EXCEL_IMPORT_WATCH_EVENT);

            if($input->getOption('dump-sql')) {
                foreach ($exporter->logger->queries as $query) {
                    if(is_array($query['params'])) {
                        $output->writeln("<info>" . $query['sql'] . " with params " . ExcelImporter::formatParameters($query['params']) . "</info>");
                    }
                    else {
                        $output->writeln("<info>" . $query['sql'] . "</info>");
                    }
                }
                $output->writeln("<info>" . count($exporter->logger->queries) . " Queries</info>");
            }

            if($input->getOption('timer')) {

                $output->writeln("<info>Temps d'exécution: " . $wathEvent->getDuration() . " ms</info>");
            }

            if(!$input->getOption('dump-sql')) {
                if($valid)
                    $output->writeln("<info>Succès de l'importation</info>", OutputInterface::VERBOSITY_NORMAL);
            }

            if(!$valid)
                $output->writeln("<error>Une erreur à eu lieu lors de l'exécution</error>", OutputInterface::VERBOSITY_NORMAL);

        }
    }

}
