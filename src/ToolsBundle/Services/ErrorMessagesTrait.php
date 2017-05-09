<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 16/04/2017
 * Time: 22:17
 */

namespace ToolsBundle\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait ErrorMessagesTrait
{
    /**
     * @var Collection
     */
    private $errors = null;

    /**
     * @return Collection
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $error
     */
    public function addError($error)
    {
        if($this->errors === null)
            $this->errors = new ArrayCollection();

        $this->errors->add($error);
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return $this->errors !== null;
    }

    /**
     * Erase all the logged errors.
     * @return void
     */
    public function clearErrors() {
        $this->errors->clear();
    }

}