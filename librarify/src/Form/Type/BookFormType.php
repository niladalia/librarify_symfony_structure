<?php

namespace App\Form\Type;

use App\Entity\Author;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use Doctrine\DBAL\Types\SmallIntType;
use IntlChar;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('base64Image', TextType::class)
            ->add('description', TextType::class)
            ->add('score', NumberType::class)
            ->add('categories', CollectionType::class, [
                'allow_add' => 'true',
                'allow_delete' => 'true',
                'entry_type' => CategoryFormType::class
            ])
            ->add('author_id', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookDto::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}
