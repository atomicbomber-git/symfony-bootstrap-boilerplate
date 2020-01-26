<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Generator as Faker;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ItemFixture extends Fixture
{
    const ITEM_GENERATED_COUNT = 100;
    const ITEM_MAX_RANDOM_QUANTITY = 100;
    /**
     * @var Faker
     */
    private $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::ITEM_GENERATED_COUNT; ++$i) {
            $item = (new Item())
                ->setName($this->faker->unique()->name)
                ->setQuantity(rand(0, self::ITEM_MAX_RANDOM_QUANTITY))
                ->setDescription($this->faker->paragraph);

            $manager->persist($item);
        }

        $manager->flush();
    }
}
