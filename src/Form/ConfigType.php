<?php

namespace App\Form;

use App\Model\Config;
use Composer\Semver\Constraint\Constraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ConfigType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numberOfResult', NumberType::class, [
                'label' => 'Nombre de résultat par recherche',
                'required' => false,
                'error_bubbling' => true,
            ])
            ->add( 'sortOrder', ChoiceType::class, [
               'choices' => [
                   'Asc' => 'asc',
                   'Desc' => 'desc'
               ],
                'required' =>true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
