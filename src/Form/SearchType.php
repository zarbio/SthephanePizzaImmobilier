<?php

namespace App\Form;

use App\Entity\Logement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('superficie',TextType::class,['required'=>false])
            ->add('nombrePieces',TextType::class,['required'=>false])
            ->add('typeLogement',ChoiceType::class,['required'=>false,'choices'=>["maison"=>"maison","appartement"=>"appartement","yourte"=>"yourte"]])
            ->add('adresse',TextType::class,['required'=>false,'mapped'=>false])
            ->add('piscine',CheckboxType::class,['required'=>false])
            ->add('exterieur',TextType::class,['required'=>false])
            ->add('garage',CheckboxType::class,['required'=>false])
            ->add('typeVente',ChoiceType::class,['required'=>false,'choices'=>["vente"=>"vente","location"=>"location"]])
            ->add('prix',TextType::class,['required'=>false,'label'=>'Prix min'])
            ->add('prixMax',TextType::class,['mapped'=>false,'required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}
