<?php

namespace App\Tests\Unit;

use App\Entity\Fruit;
use App\Entity\Nutrition;
use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitServiceTest extends KernelTestCase
{
    public function testFruitInsertion(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $fruitRepo = $container->get(FruitRepository::class);
        
        $fruit = new Fruit();
        $fruit->setName("Apple");
        $fruit->setFamily("Test Family");
        $fruit->setTaxoOrder("Test order");
        $fruit->setGenus('Test Genus');
        $fruit->setRemoteId(1);

        $nutrition = new Nutrition();
        $nutrition->setCalories(10);
        $nutrition->setCarbohydrates(5);
        $nutrition->setFat(.01);
        $nutrition->setSugar(20);
        $nutrition->setProtein(1);

        $fruit->setNutrition($nutrition);

        $fruitRepo->save($fruit, true);

        list($total, $fruits) = $fruitRepo->findFruits();
        
        $this->assertEquals($total, 1, "Fruit count total should be one");
        $this->assertEquals($fruits[0]['name'], "Apple", "First fruit name should be 'Apple'");
    }
}
