<?php

namespace ToolsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ValidatorBuilder;

class ToolsExcelExportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tools:excel:export')
            ->setDescription('Provide a way to export informations into an Excel format')
            ->addArgument('manifest', InputArgument::REQUIRED, 'The excel manifest who defined what is exported')
            ->addArgument('output', InputArgument::REQUIRED, 'The name of the file where informations are being exported to')
            ->addUsage("tools:excel:export --manifest manifest.yml --output output.yml")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $system = $this->getContainer()->get('filesystem');
        $finder = new Finder();

        $manifestUri = $input->getArgument('manifest');
        $outputUri = $input->getArgument('output');


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

        $excelViolations = $validator->validate($outputUri, [
            new Regex([
                'pattern' => "/.+\\.xls/",
                'match' => true,
                "message" => "Le Fichier Output doit porter l'extension '.xls'"
            ])
        ]);

        if (0 !== count($excelViolations)) {
            // there are errors, now you can show them
            foreach ($excelViolations as $violation) {
                $output->writeln($violation->getMessage());
            }
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
            $exporter = $this->getContainer()->get('tools.excel.exporter');
            foreach ($finder as $fileInfo) {
                $exporter->export($fileInfo->getRealPath(), $outputUri);
            }

        }
    }

}
