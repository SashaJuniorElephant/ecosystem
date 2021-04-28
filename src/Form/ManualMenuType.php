<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class ManualMenuType extends AbstractType
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
            ->add('dimension', IntegerType::class, [
                'label' => 'dimension',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThanOrEqual(2),
                ],
            ])
            ->add('amountSteps', IntegerType::class, [
                'label' => 'amount_of_steps',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new Range([
                        'min' => 1,
                        'max' => 20,
                    ]),
                ],
            ])
            ->add('amountSimplePlants', IntegerType::class, [
                'label' => 'amount_of_simple_plants',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThan(0),
                ],
            ])
            ->add('amountPoisonPlants', IntegerType::class, [
                'label' => 'amount_of_poison_plants',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThan(0),
                ],
            ])
            ->add('amountHerbivores', IntegerType::class, [
                'label' => 'amount_of_herbivores',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThan(0),
                ],
            ])
            ->add('amountPredators', IntegerType::class, [
                'label' => 'amount_of_predators',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThan(0),
                ],
            ])
            ->add('amountBigPredators', IntegerType::class, [
                'label' => 'amount_of_big_predators',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThan(0),
                ],
            ])
            ->add('amountVisitors', IntegerType::class, [
                'label' => 'amount_of_visitors',
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    new GreaterThan(0),
                ],
            ])
            ->add('useSessions', CheckboxType::class, [
                'label' => 'use_sessions',
                'required' => false,
            ])
            ->add('sendFields', SubmitType::class, [
                'label' => 'start_observation',
            ])
        ;
    }
}
