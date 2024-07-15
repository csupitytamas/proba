<?php

namespace App\Controller\Interfaces;

interface EntityInterface
{
    public function gelAll(): false|string;
    public function get(): false|string;
    public function create(): false|string;
    public function update(): false|string;
    public function delete(): false|string;
}