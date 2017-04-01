<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 17:51
 */

namespace ToolsBundle\Services\ExcelExporter;


use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelMappingParser\ManifestParser;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelGenerateHeaderNodeVisitor;
use PHPExcel;
use PHPExcel_Worksheet;

class ExcelExporter
{
    /**
     * @var ManifestParser
     */
    private $parser;

    /**
     * @var Factory
     */
    private $excel;

    /**
     * @var Filesystem
     */
    private $system;

    /**
     * ExcelExporter constructor.
     * @param ManifestParser $parser
     * @param Factory $excel
     */
    public function __construct(Filesystem $system, ManifestParser $parser, Factory $excel)
    {
        $this->parser = $parser;
        $this->excel = $excel;
        $this->system = $system;
    }

    public function export($yamlPath, $excelPath) {
        $manifest = $this->parser->parse($yamlPath);

        if($this->system->exists($excelPath))
            $this->system->remove($excelPath);

        $this->system->touch($excelPath);

        $excelFile = $this->excel->createPHPExcelObject($excelPath);
        $excelFile->removeSheetByIndex();
        $visitor = new ExcelGenerateHeaderNodeVisitor($manifest);

        $this->exportSheets($excelFile, $visitor, $manifest->getSheets(), $manifest->getEntityNodes());

        $this->excel->createWriter($excelFile)->save($excelPath);
    }

    public function exportSheets(PHPExcel $excelFile, ExcelGenerateHeaderNodeVisitor $visitor, ParameterBag $sheets, ParameterBag $entityNodes) {

        foreach ($sheets as $sheetName => $sheetBag) {
            $workSheet = $excelFile->createSheet();
            $workSheet->setTitle($sheetName);
            $visitor->setWorkSheet($workSheet);
            $this->exportSheet($visitor, $sheetBag, $entityNodes);
        }


    }

    public function exportSheet(ExcelGenerateHeaderNodeVisitor $visitor, ParameterBag $sheets, ParameterBag $entityNodes) {
        foreach ($sheets->getIterator() as $identifier => $entityInfos) {
            $entityNodes->get($identifier)->accept($visitor);
        }
    }

}