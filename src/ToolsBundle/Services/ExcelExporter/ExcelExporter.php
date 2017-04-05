<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 01/04/2017
 * Time: 17:51
 */

namespace ToolsBundle\Services\ExcelExporter;


use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelMappingParser\ManifestParser;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\AbstractNodeVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelEntityHydratorNodeVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelExportVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\HeaderBuilderVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\QueryBuilderNodeVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelGenerateHeaderNodeVisitor;
use PHPExcel;
use PHPExcel_Worksheet;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\QueryBuilderVisitor;

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
     * @var EntityManager
     */
    private $em;

    /**
     * @var HeaderBuilderVisitor
     */
    private $headerBuilderVisitor;
    /**
     * @var QueryBuilderVisitor
     */
    private $queryBuilderVisitor;
    /**
     * @var ExcelExportVisitor
     */
    private $excelExporterVisitor;

    /**
     * ExcelExporter constructor.
     * @param ManifestParser $parser
     * @param Factory $excel
     */
    public function __construct(EntityManager $entityManager, Filesystem $system, ManifestParser $parser, Factory $excel)
    {
        $this->em = $entityManager;
        $this->parser = $parser;
        $this->excel = $excel;
        $this->system = $system;

        $this->queryBuilderVisitor = new QueryBuilderVisitor($this->em);
        $this->excelExporterVisitor = new ExcelExportVisitor($this->em);
        $this->headerBuilderVisitor = new HeaderBuilderVisitor();
    }

    public function export($yamlPath, $excelPath) {
        $manifest = $this->parser->parse($yamlPath);

        if($this->system->exists($excelPath))
            $this->system->remove($excelPath);

        $this->system->touch($excelPath);

        $excelFile = $this->excel->createPHPExcelObject($excelPath);
        $excelFile->removeSheetByIndex();

        $this->exportSheets($excelFile, $manifest->getSheets(), $manifest->getEntityNodes());
        $this->excel->createWriter($excelFile)->save($excelPath);
    }

    public function exportSheets(PHPExcel $excelFile, ParameterBag $sheets, ParameterBag $entityNodes) {

        foreach ($sheets as $sheetName => $sheetBag) {
            $workSheet = $excelFile->createSheet();
            $workSheet->setTitle($sheetName);
            $this->headerBuilderVisitor->setWorkSheet($workSheet);
            $this->excelExporterVisitor->setWorkSheet($workSheet);
            $this->exportSheet($sheetBag, $entityNodes);
        }


    }

    public function exportSheet(ParameterBag $sheets, ParameterBag $entityNodes) {
        foreach ($sheets->getIterator() as $identifier => $entityInfos) {
            $query = $this->queryBuilderVisitor->getQuery($entityNodes->get($identifier));
            $this->headerBuilderVisitor->generateHeader($entityNodes->get($identifier), $entityInfos);
            $this->excelExporterVisitor->generateExcelTable($query, $entityNodes->get($identifier), $entityInfos);
        }
    }

}