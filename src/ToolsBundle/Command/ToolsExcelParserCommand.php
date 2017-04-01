<?php

namespace ToolsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use ToolsBundle\Services\ExcelMappingParser\ManifestParser;

class ToolsExcelParserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tools:excel:parser')
            ->setDescription('It\'s just a command to test valid services')
            ->addArgument('file', InputArgument::REQUIRED, 'Nom du fichier yaml manifest')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //__DIR__ . "/../../../../" --> Root Folder
        //__DIR__ . "/../Resources/ExcelManifests/"

        $finder = new Finder();

        $finder->in(__DIR__ . "/../Resources/ExcelManifests/")->name($input->getArgument('file'));

        /**
         * @var SplFileInfo $excelManifest
         */
        $excelManifest = null;

        if(!$finder->count() == 1) {
            $output->writeln("Ce fichier n'existe pas");
            return;
        }

        foreach ($finder as $file) {
            $excelManifest = $file;
        }

        $exporter = $this->getContainer()->get('tools.excel.exporter');

        $exporter->export($excelManifest->getRealPath(), "file.xls");
    }

}
