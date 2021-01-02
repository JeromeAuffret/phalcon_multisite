<?php

namespace Demo1\Models;

use Base\Models\Application;
use Phalcon\Mvc\Model\ResultInterface;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\ModelInterface;

/**
 * Class Lot
 * @package Demo1\Models
 */
class Lot extends \Base\Models\BaseModel
{
    protected $IdLot;
    protected $IdTransaction;
    protected $IdTraitement;
    protected $NomFlux;
    protected $DateLot;
    protected $TypeFlux;
    protected $ClefNumLot;
    protected $NomLot;
    protected $Statut;
    protected $NbPlisIdx;
    protected $NbPlisCons;
    protected $NbPlisDest;

    /**
     * @return mixed
     */
    public function getIdLot()
    {
        return $this->IdLot;
    }

    /**
     * @param mixed $IdLot
     */
    public function setIdLot($IdLot): void
    {
        $this->IdLot = $IdLot;
    }

    /**
     * @return mixed
     */
    public function getIdTransaction()
    {
        return $this->IdTransaction;
    }

    /**
     * @param mixed $IdTransaction
     */
    public function setIdTransaction($IdTransaction): void
    {
        $this->IdTransaction = $IdTransaction;
    }

    /**
     * @return mixed
     */
    public function getIdTraitement()
    {
        return $this->IdTraitement;
    }

    /**
     * @param mixed $IdTraitement
     */
    public function setIdTraitement($IdTraitement): void
    {
        $this->IdTraitement = $IdTraitement;
    }

    /**
     * @return mixed
     */
    public function getNomFlux()
    {
        return $this->NomFlux;
    }

    /**
     * @param mixed $NomFlux
     */
    public function setNomFlux($NomFlux): void
    {
        $this->NomFlux = $NomFlux;
    }

    /**
     * @return mixed
     */
    public function getDateLot()
    {
        return $this->DateLot;
    }

    /**
     * @param mixed $DateLot
     */
    public function setDateLot($DateLot): void
    {
        $this->DateLot = $DateLot;
    }

    /**
     * @return mixed
     */
    public function getTypeFlux()
    {
        return $this->TypeFlux;
    }

    /**
     * @param mixed $TypeFlux
     */
    public function setTypeFlux($TypeFlux): void
    {
        $this->TypeFlux = $TypeFlux;
    }

    /**
     * @return mixed
     */
    public function getClefNumLot()
    {
        return $this->ClefNumLot;
    }

    /**
     * @param mixed $ClefNumLot
     */
    public function setClefNumLot($ClefNumLot): void
    {
        $this->ClefNumLot = $ClefNumLot;
    }

    /**
     * @return mixed
     */
    public function getNomLot()
    {
        return $this->NomLot;
    }

    /**
     * @param mixed $NomLot
     */
    public function setNomLot($NomLot): void
    {
        $this->NomLot = $NomLot;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->Statut;
    }

    /**
     * @param mixed $Statut
     */
    public function setStatut($Statut): void
    {
        $this->Statut = $Statut;
    }

    /**
     * @return mixed
     */
    public function getNbPlisIdx()
    {
        return $this->NbPlisIdx;
    }

    /**
     * @param mixed $NbPlisIdx
     */
    public function setNbPlisIdx($NbPlisIdx): void
    {
        $this->NbPlisIdx = $NbPlisIdx;
    }

    /**
     * @return mixed
     */
    public function getNbPlisCons()
    {
        return $this->NbPlisCons;
    }

    /**
     * @param mixed $NbPlisCons
     */
    public function setNbPlisCons($NbPlisCons): void
    {
        $this->NbPlisCons = $NbPlisCons;
    }

    /**
     * @return mixed
     */
    public function getNbPlisDest()
    {
        return $this->NbPlisDest;
    }

    /**
     * @param mixed $NbPlisDest
     */
    public function setNbPlisDest($NbPlisDest): void
    {
        $this->NbPlisDest = $NbPlisDest;
    }


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService('db');
        $this->setSource("ref_lots");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Application[]|Application|ResultSetInterface
     */
    public static function find($parameters = null): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Application|ResultInterface|ModelInterface
     */
    public static function findFirst($parameters = null): ?ModelInterface
    {
        return parent::findFirst($parameters);
    }


}