<?php

namespace App\Form;

use App\Entity\Games;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContinueMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('game', EntityType::class, [
                'class' => Games::class,
                'choice_label' => 'name',
                'label' => 'game_name',
                'placeholder' => 'select_game',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('sendFile', SubmitType::class, [
                'label' => 'continue_observation',
            ])
        ;
    }
}
