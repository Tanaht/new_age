<?php

namespace ToolsBundle\Command;

use FOS\RestBundle\Validator\Constraints\Regex;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Validation;

class ToolsExcelImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tools:excel:import')
            ->setDescription('Provide a way to import information from an Excel file')
            ->addArgument('manifest', InputArgument::REQUIRED, 'The excel manifest who defined what is imported')
            ->addArgument('input', InputArgument::REQUIRED, 'The name of the file where informations are being extracted and imported to database')
            ->addUsage("tools:excel:export <manifest.yml> <input.xls>")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
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
            $exporter = $this->getContainer()->get('tools.excel.importer');
            foreach ($finder as $fileInfo) {
                if(!$exporter->import($fileInfo->getRealPath(), $inputUri)) {
                    $exporter->getErrors()->forAll(function ($key, $error) use ($output) {
                        //                        Formatter Sample:
                        //                        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
                        //                        $output->getFormatter()->setStyle('fire', $style);
                        //                        $output->writeln('<fire>foo</fire>');

                        $output->writeln("<error>$error</error>", OutputInterface::VERBOSITY_VERBOSE);
                        return true;
                    });
                    $output->writeln("Erreur lors de l'importation", OutputInterface::VERBOSITY_NORMAL);
                    return;
                }

            }

            $output->writeln("<info>Succès de l'importation</info>", OutputInterface::VERBOSITY_NORMAL);

        }
    }

}
