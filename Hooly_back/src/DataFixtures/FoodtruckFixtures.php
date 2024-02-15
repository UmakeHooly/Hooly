<?php
namespace App\DataFixtures;

use App\Entity\Foodtruck;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FoodtruckFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $foodtruckNames = ['Foodtruck 1', 'Foodtruck 2', 'Foodtruck 3','Foodtruck 4', 'Foodtruck 5', 'Foodtruck 6','Foodtruck 7', 'Foodtruck 8', 'Foodtruck 9'];

        foreach ($foodtruckNames as $name) {
            $foodtruck = new Foodtruck();
            $foodtruck->setNom($name);

            $manager->persist($foodtruck);
        }

        $manager->flush();
    }
}
