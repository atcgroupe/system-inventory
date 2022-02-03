<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom', 'required' => true])
            ->add('isProvider', CheckboxType::class, ['label' => 'Fournisseur', 'required' => false])
            ->add('isManufacturer', CheckboxType::class, ['label' => 'Fabricant', 'required' => false])
            ->add(
                'technicalDepartmentPhone',
                TextType::class,
                [
                    'label' => 'Numéro du service technique',
                    'required' => false
                ]
            )->add(
                'technicalDepartmentProcedure',
                TextareaType::class,
                [
                    'label' => 'Procédure',
                    'help' => 'Procédure de contact du service technique',
                    'required' => false,
                    'attr' => [
                        'rows' => 6,
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
