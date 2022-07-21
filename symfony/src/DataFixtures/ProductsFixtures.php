<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
//        for ($i = 0; $i < 20; $i++) {
//            $categories = $manager->getRepository(Category::class);
//            $categories = $categories->findAll();
//            foreach ($categories as $category) {
//                $product = new Product();
//                $product->setName('product ' . $i);
//                $product->setCode('2022');
//                $product->setPrice(mt_rand(10, 100));
//                $product->setDescription("Описание");
//                $product->setCategory($category);
//                $product->setWarehouse(mt_rand(10, 100));
//                $manager->persist($product);
//            }
//        }
//
//        $manager->flush();
    }
}