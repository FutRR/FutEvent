<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('image', FileType::class, [
                'row_attr' => ['class' => 'form-group file-upload'],
                'mapped' => false,
                'label' => 'Image (File must not exceed 3MB)',
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '3000k',
                        mimeTypes: [
                            'image/webp',
                            'image/jpeg',
                            'image/png',
                        ],
                        notFoundMessage: 'The file could not be found',
                        notReadableMessage: 'The file is not readable',
                        maxSizeMessage: 'The image is too large. Maximum allowed size is 3MB',
                        mimeTypesMessage: 'Please upload a valid image',
                    )
                ]
            ])
            ->add('datetime_start', DateTimeType::class, [
                'row_attr' => ['class' => 'form-group'],
                'label' => 'Starts On',
            ])
            ->add('datetime_end', DateTimeType::class, [
                'row_attr' => ['class' => 'form-group'],
                'label' => 'Ends On',

            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category',
                'choice_value' => 'id',
                'data' => $options['category_id'] ?
                    $this->entityManager->getRepository(Category::class)->find($options['category_id']) :
                    null,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'category_id' => null,
        ]);
    }
}
