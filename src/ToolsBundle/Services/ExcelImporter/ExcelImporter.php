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
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ToolsBundle\Services\ExcelMappingParser\ManifestParser;
use PHPExcel;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelEntityImporterVisitor;
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
     * @var DebugStack
     */
    public $logger;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var bool
     */
    private $dryRun;

    /**
     * ExcelExporter constructor.
     * @param ManifestParser $parser
     * @param Factory $excel
     */
    public function __construct(EntityManager $manager, Filesystem $system, ManifestParser $parser, Factory $excel, ValidatorInterface $validator)
    {
        $this->em = $manager;
        $this->logger = new DebugStack();
        $this->logger->enabled = false;

        /** @var LoggerChain $logger */
        $logger = $this->em->getConnection()->getConfiguration()->getSQLLogger();
        if(!get_class($logger) === LoggerChain::class)
            $logger->addLogger($this->logger);
        else {
            $this->em->getConnection()->getConfiguration()->setSQLLogger($this->logger);
        }

        $this->parser = $parser;
        $this->excel = $excel;
        $this->system = $system;
        $this->headerValidator = new HeaderValidatorVisitor();
        $this->indexValidator = new ExcelIndexValidatorVisitor();
        $this->errors = new ArrayCollection();
        $this->validator = $validator;
    }

    /**
     * @param $yamlPath
     * @param $excelPath
     * @param bool $dryRun whether to effectively do the import operations or not.
     * @return bool
     */
    public function import($yamlPath, $excelPath, $dryRun = false) {
        $this->logger->enabled = false;
        $this->dryRun = $dryRun;
        $manifest = $this->parser->parse($yamlPath);

        if(!$this->system->exists($excelPath)) {
            $this->errors->add("Le document '" . $excelPath . "' est introuvable");
            return false;
        }


        $excelFile = $this->excel->createPHPExcelObject($excelPath);

        if(!$this->validateSheets($excelFile, $manifest->getSheets(), $manifest->getEntityNodes())) {
            return false;
        }

        $this->logger->enabled = true;
        $this->em->beginTransaction();

        $validateState = $this->importSheets($excelFile, $manifest->getSheets(), $manifest->getEntityNodes());

        try {

            if($validateState === false)
                $this->em->rollback();
            else {
                if(!$this->dryRun)
                    $this->em->commit();
                else
                    $this->em->rollback();
            }
        }
        catch(\Exception $e) {
            $this->errors->add($e->getMessage());
            return false;
        }

        if($validateState === false)
            return false;
        return true;
    }

    public function importSheets(PHPExcel $excelFile, ParameterBag $sheets, ParameterBag $entityNodes) {
        $valid = true;
        foreach ($sheets as $sheetName => $sheetBag) {
            $importer = new ExcelEntityImporterVisitor($this->em);

            $importer->setWorksheet($excelFile->getSheetByName($sheetName));
            if(!$this->importSheet($importer, $sheetBag, $entityNodes)) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    /**
     * @param ExcelEntityImporterVisitor $importer
     * @param ParameterBag $sheets
     * @param ParameterBag $entityNodes
     * @return bool
     */
    public function importSheet(ExcelEntityImporterVisitor $importer, ParameterBag $sheets, ParameterBag $entityNodes)
    {
        $valid = true;
        foreach ($sheets->getIterator() as $identifier => $entityInfos) {

            $imports = $importer->importExcelTable($entityNodes->get($identifier), $entityInfos);

            if ($importer->hasErrors()) {
                $errors = $importer->getErrors();

                foreach ($errors as $error)
                    $this->errors->add($error);

                $valid = false;
                break;
            }

            foreach ($imports->getIterator() as $unvalidatedObject) {
                $errors = $this->validator->validate($unvalidatedObject, null);

                if ($errors->count() > 0) {
                    foreach ($errors as $error)
                        $this->errors->add($error);
                    $valid = false;
                }
                else {
                    $this->em->persist($unvalidatedObject);
                }
            }
            $this->em->flush();

        }
        return $valid;
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
            if (!$this->headerValidator->validateHeader($entityNodes->get($identifier), $entityInfos)) {
                $valid = false;
                break;
            } else {
                if (!$this->indexValidator->validate($entityNodes->get($identifier), $entityInfos)) {
                    foreach ($this->indexValidator->getErrors() as $error) {
                        $this->errors->add($error);
                    }
                    $valid = false;
                    break;
                }
            }

            if (!$valid)
                break;

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

    /**
     * @author Copy Paste From DBALException Class of Doctrine Vendor
     * Returns a human-readable representation of an array of parameters.
     * This properly handles binary data by returning a hex representation.
     *
     * @param array $params
     *
     * @return string
     */
    public static function formatParameters(array $params)
    {
        return '[' . implode(', ', array_map(function ($param) {
                $json = @json_encode($param);

                if (! is_string($json) || $json == 'null' && is_string($param)) {
                    // JSON encoding failed, this is not a UTF-8 string.
                    return '"\x' . implode('\x', str_split(bin2hex($param), 2)) . '"';
                }

                return $json;
            }, $params)) . ']';
    }

}