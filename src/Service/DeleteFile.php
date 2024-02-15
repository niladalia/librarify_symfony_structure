<?php

namespace App\Service;

use League\Flysystem\FilesystemOperator;

class DeleteFile
{
    private $defaultStorage;

    public function __construct(FilesystemOperator $defaultStorage)
    {
        $this->defaultStorage = $defaultStorage;
    }

    public function __invoke(string $filename)
    {
        $this->defaultStorage->delete($filename);
    }
}
