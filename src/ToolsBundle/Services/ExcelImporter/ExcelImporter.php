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
use Doctrine\DBAL\Logging\EchoSQLLogger;
use Doctrine\ORM\EntityManager;
use Liuggio\ExcelBundle\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ToolsBundle\Services\DumpSQLLogger;
use ToolsBundle\Services\ExcelMappingParser\ManifestParser;
use PHPExcel;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelEntityImporterVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\ExcelIndexValidatorVisitor;
use ToolsBundle\Services\ExcelNodeVisitor\Visitor\HeaderValidatorVisitor;
use VisiteurBundle\Entity\Etape;

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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ExcelExporter constructor.
     * @param ManifestParser $parser
     * @param Factory $excel
     */
    public function __construct(EntityManager $entityManager, Filesystem $system, ManifestParser $parser, Factory $excel, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->parser = $parser;
        $this->excel = $excel;
        $this->system = $system;
        $this->headerValidator = new HeaderValidatorVisitor();
        $this->indexValidator = new ExcelIndexValidatorVisitor();
        $this->errors = new ArrayCollection();
        $this->validator = $validator;
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

            $entityFinder = new ExcelEntityImporterVisitor($this->em);

            $entityFinder->setWorksheet($this->indexValidator->getWorksheet());


            $imports = $entityFinder->importExcelTable($entityNodes->get($identifier), $entityInfos);

            dump($imports->count() . " Entities Persisted");

            if ($entityFinder->hasErrors()) {
                $errors = $entityFinder->getErrors();

                foreach ($errors as $error)
                    $this->errors->add($error);

                $valid = false;
                break;
            }

            foreach ($imports->getIterator() as $unvalidatedObject) {
                $errors = $this->validator->validate($unvalidatedObject);
                if ($errors->count() > 0) {
                    foreach ($errors as $error)
                        $this->errors->add($error);
                    $valid = false;
                }
            }

            if (!$valid)
                break;

            $logger = new DebugStack();

            $this->em->getConfiguration()->setSQLLogger($logger);
            $this->em->beginTransaction();

            foreach ($imports as $import) {
                $this->em->persist($import);

            }

            foreach ($this->em->getUnitOfWork()->getScheduledCollectionUpdates() as $entityUpdate) {
                dump("Updating: " . get_class($entityUpdate));
            }

            foreach ($this->em->getUnitOfWork()->getScheduledEntityInsertions() as $entityInsertion) {
                dump("Inserting: " . get_class($entityInsertion). ' ' . $entityInsertion->getName());
            }
            foreach ($this->em->getUnitOfWork()->getScheduledCollectionUpdates() as $collectionUpdate) {
                foreach ($collectionUpdate as $entity) {
                    dump("Updating Collection Item: " . get_class($entity));
                }
            }

            try{
                $this->em->flush();
                $this->em->commit();
                dump("Requests: ");
                foreach ($logger->queries as $sql) {
                    dump("SQL: " . $sql);
                }
            }
            catch(\Exception $e) {
                $this->em->rollback();
                $this->errors->add($e->getMessage());
                dump("Requests: ");
                foreach ($logger->queries as $sql) {
                    dump("SQL: " . $sql);
                }
                $valid = false;
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