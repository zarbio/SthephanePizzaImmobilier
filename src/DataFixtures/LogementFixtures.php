<?php

namespace App\DataFixtures;

use App\Entity\Logement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LogementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeLogement = ['maison','appartement','yourte'];
        $typeVente = ['vente','location'];
     for ($i=0;$i < 30;$i++)
     {
         $logement = new Logement();
         $logement->setSuperficie(mt_rand(50,550));
         $logement->setNombrePieces(mt_rand(2,8));
         $logement->setTypeLogement($typeLogement[mt_rand(0,2)]);
         $logement->setAdresse('logement'.$i);
         $logement->setPiscine(mt_rand(0,1));
         $logement->setExterieur(mt_rand(25,100));
         $logement->setGarage(mt_rand(0,1));
         $logement->setTypeVente($typeVente[mt_rand(0,1)]);
         $logement->setPrix(mt_rand(5000,500000));
         $date = new \DateTime();
         $date->setDate(mt_rand(2018,2022),mt_rand(1,12),mt_rand(1,28));
         $logement->setDateParution($date);
         $manager->persist($logement);
     }

        $manager->flush();
    }
}
