<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder
                ->add('username', TextType::class, [
                    'label' => 'label.username',
                    'disabled' => false,
                ])
                ->add('fullName', TextType::class, [
                    'label' => 'label.fullname',
                ])
                ->add('email', EmailType::class, [
                    'label' => 'label.email',
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'label.password',
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
