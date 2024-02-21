<?php

namespace App\Service\FileUploader;

use App\Form\Model\BookDto;
use App\Interfaces\FileUploaderInterface;
use League\Flysystem\FilesystemOperator;

class FileUploaderLocal implements FileUploaderInterface
{
    /*
    Aquest es un servei que s'encarrega de guardar els fitxers al nostre servidor, en local.
    */
    private $defaultStorage;

    public function __construct(FilesystemOperator $defaultStorage)
    {
        $this->defaultStorage = $defaultStorage;
    }

    public function uploadFile(BookDto $bookDto): string
    {
        $base64File = $bookDto->base64Image;
        $extension = explode('/', mime_content_type($base64File))[1];
        $data = explode(',', $base64File);
        $filename = sprintf('%s.%s', uniqid('local_book_', true), $extension);
        $this->defaultStorage->write($filename, base64_decode($data[1]));
        return $filename;
    }
}
