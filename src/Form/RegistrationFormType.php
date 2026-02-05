<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'form-group'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'row_attr' => ['class' => 'form-checkbox'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue(message: 'You should agree to our terms.'),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'label' => 'Password',
                    'row_attr' => ['class' => 'form-group'],
                    'attr' => ['autocomplete' => 'new-password']
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'row_attr' => ['class' => 'form-group'],
                    'attr' => ['autocomplete' => 'new-password']
                ],
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Please enter a password'),
                    new Length(min: 6, max: 4096, minMessage: 'Your password should be at least {{ limit }} characters'),
                    new Regex(
                        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^()_\-+=\[\]{};:\'",.<>\/\\|`~]).{8,}$/',
                        message: 'Your password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
