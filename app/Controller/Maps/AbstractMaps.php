<?php

namespace App\Controller\Maps;

abstract class AbstractMaps
{
    abstract protected function getFieldId();

    abstract protected function getAllData();

    abstract protected function newWings();

    abstract protected function newPoles();

    abstract protected function deleteWings();

    abstract protected function deletePoles();

    abstract protected function getWingsOnField();

    abstract protected function getPolesOnField();
}