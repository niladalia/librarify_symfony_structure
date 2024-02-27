<?php

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

# Aquest serializer NO funciona ja que el API Platform component no esta configurat, aquesta clase serveig per a serialitzar els objectes per mostrarlos.
class BookNormalizer implements NormalizerAwareInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($book, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($book, $format, $context);
        $data['title'] = $book->getTitle() . "_asereje";
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Book;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Book::class => true,
        ];
    }

    public function setNormalizer(NormalizerInterface $normalizer): void {}
}
