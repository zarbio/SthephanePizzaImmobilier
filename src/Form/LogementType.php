<?php

namespace App\Form;

use App\Entity\Logement;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('superficie',NumberType::class)
            ->add('nombrePieces')
            ->add('typeLogement',ChoiceType::class,['choices'=>["maison"=>"maison","appartement"=>"appartement","yourte"=>"yourte"]])
            ->add('adresse')
            ->add('piscine')
            ->add('exterieur',NumberType::class)
            ->add('garage')
            ->add('typeVente',ChoiceType::class,['choices'=>["vente"=>"vente","location"=>"location"]])
            ->add('prix')
            ->add('image',FileType::class,['label'=>'Ajouter une image du logement','required'=>false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}
