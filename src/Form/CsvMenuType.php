<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CsvMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'game_name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3])
                ],
            ])
            ->add('file', FileType::class, [
                'label' => 'select_csv_file',
                'attr' => [ 'accept' => '.csv' ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('useSessions', CheckboxType::class, [
                'label' => 'use_sessions',
                'required' => false,
            ])
            ->add('sendFile', SubmitType::class, [
                'label' => 'start_observation'
            ])
        ;
    }

}
