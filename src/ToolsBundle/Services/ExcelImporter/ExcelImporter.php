<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 05/04/2017
 * Time: 11:34
 */

namespace ToolsBundle\Services\ExcelImporter;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ParameterBag;
use ToolsBundle\Services\ExcelMappingParser\ManifestParser;
use PHPExcel;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelEntityFinderVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelIndexValidatorVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\HeaderValidatorVisitor;

class ExcelImporter
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
     * @var HeaderValidatorVisitor
     */
    private $headerValidator;

    /**
     * @var ExcelIndexValidatorVisitor
     */
    private $indexValidator;

    /**
     * @var Collection
     */
    private $errors;

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
        $this->headerValidator = new HeaderValidatorVisitor();
        $this->indexValidator = new ExcelIndexValidatorVisitor();
        $this->errors = new ArrayCollection();
    }

    public function import($yamlPath, $excelPath) {
        $manifest = $this->parser->parse($yamlPath);

        if(!$this->system->exists($excelPath)) {
            $this->errors->add("Le document '" . $excelPath . "' est introuvable");
            return false;
        }


        $excelFile = $this->excel->createPHPExcelObject($excelPath);

        if(!$this->validateSheets($excelFile, $manifest->getSheets(), $manifest->getEntityNodes())) {
            return false;
        }

        return true;
    }

    public function validateSheets(PHPExcel $excelFile, ParameterBag $sheets, ParameterBag $entityNodes) {
        $valid = true;
        foreach ($sheets as $sheetName => $sheetBag) {
            if(!$excelFile->sheetNameExists($sheetName)) {
                $this->errors->add("La feuille '" . $sheetName . "' n'existe pas");
                $valid = false;
                break;
            }

            $workSheet = $excelFile->getSheetByName($sheetName);
            $this->headerValidator->setWorksheet($workSheet);
            $this->indexValidator->setWorksheet($workSheet);
            if(!$this->validateSheet($sheetBag, $entityNodes)) {
                foreach ($this->headerValidator->getErrors() as $error) {
                    $this->errors->add($error);
                }
                $valid = false;
                break;
            }
        }

        return $valid;


    }

    public function validateSheet(ParameterBag $sheets, ParameterBag $entityNodes) {
        $valid = true;
        foreach ($sheets->getIterator() as $identifier => $entityInfos) {
            if(!$this->headerValidator->validateHeader($entityNodes->get($identifier), $entityInfos)) {
                $valid = false;
                break;
            }
            else {
                if(!$this->indexValidator->validate($entityNodes->get($identifier), $entityInfos)) {
                    foreach ($this->indexValidator->getErrors() as $error) {
                        $this->errors->add($error);
                    }
                    $valid = false;
                    break;
                }
            }

            $entityFinder = new ExcelEntityFinderVisitor($this->em);

            $entityFinder->setWorksheet($this->indexValidator->getWorksheet());

            try {
                $entityFinder->findEntity(6, $entityNodes->get($identifier), $entityInfos);
            }
            catch(EntityNotFoundException $e) {
                $this->errors->add($e->getMessage());
                $valid = false;
                break;
            }
        }
        return $valid;
    }

    /**
     * @return Collection
     */
    public function getErrors()
    {
        return $this->errors;
    }



}