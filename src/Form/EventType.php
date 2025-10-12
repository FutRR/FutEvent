<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
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
            ])
            ->add('datetime_start', DateTimeType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('datetime_end', DateTimeType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
