<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoriesData = [
            0 => [
                'name' => 'Обувь'
            ],
            1 => [
                'name' => 'Игрушки'
            ],
            2 => [
                'name' => 'Спорт'
            ],
            3 => [
                'name' => 'Продукты'
            ],
            4 => [
                'name' => 'Книги'
            ],
            5 => [
                'name' => 'Здоровье'
            ],
            6 => [
                'name' => 'Дом'
            ],
            7 => [
                'name' => 'Электроника'
            ]
        ];

        foreach ($categoriesData as $category) {
            $newCategory = new Category();
            $newCategory->setName($category['name']);
            $newCategory->setCode('');
            $manager->persist($newCategory);
        }

        $manager->flush();
    }
}