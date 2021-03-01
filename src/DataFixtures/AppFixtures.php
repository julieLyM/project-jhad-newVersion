<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        # Création des catégories
        $prestation = new Category();
        $prestation->setName('service')->setSlug('service');
        $manager->persist($prestation);

        $eBoutique= new Category();
        $eBoutique->setName('E-boutique')->setSlug('boutique');
        $manager->persist($eBoutique);

        $manager->flush();

        # Création d'un User
        $user = new User();
        $user->setFirstname('test')
            ->setLastname('test')
            ->setEmail('juju@juju.ju')
            ->setPassword('demo')
            ->setPhone(0160606060)
            ->setBirthday(new \DateTime())
            ->setAddress('10 rue de paris')
            ->setCity('Paris')
            ->setZipcode(75001)
            ->setCountry('FRANCE')
            ->setRoles(['ROLE_USER']);

        $user1 = new User();
        $user1->setFirstname('toto')
            ->setLastname('toto1')
            ->setEmail('toto@toto.to')
            ->setPassword('demo')
            ->setPhone(0160606060)
            ->setBirthday(new \DateTime())
            ->setAddress('10 rue de toto')
            ->setCity('Versailles')
            ->setZipcode(78000)
            ->setCountry('FRANCE')
            ->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->persist($user1);
        $manager->flush();

        /*product servcice*/
        $productService = new Product();
        $productService->setName('manucure simple')
            ->setSlug('lorem-ipsum-dolor-')
            ->setImage('https://via.placeholder.com/500')
            ->setDescription('lorem blalbalbalbalbalablalb')
            ->setCreatedAt(new \DateTime())
            ->setStock(30)
            ->setUser($user)
            ->setPrice(55)
            ->setCategorie($prestation);

        /*product boutique*/
        $productEBoutique = new Product();
        $productEBoutique->setName('vernis à ongles')
            ->setSlug('lorem-ipsum-dolor-')
            ->setImage('https://via.placeholder.com/500')
            ->setDescription('lorem blalbalbalbalbalablalb')
            ->setCreatedAt(new \DateTime())
            ->setStock(10)
            ->setUser($user1)
            ->setPrice(15)
            ->setCategorie($eBoutique);

        $productEBoutique2 = new Product();
        $productEBoutique2->setName('vernis à ongles')
            ->setSlug('lorem-ipsum-dolor-')
            ->setImage('https://via.placeholder.com/500')
            ->setDescription('lorem blalbalbalbalbalablalb')
            ->setCreatedAt(new \DateTime())
            ->setStock(3)
            ->setUser($user)
            ->setPrice(15)
            ->setCategorie($eBoutique);


        # On demande l'enregistrement
        $manager->persist($productService);
        $manager->persist($productEBoutique);
        $manager->persist($productEBoutique2);

        $manager->flush();



        # Création des commandes
        $order1 = new Order();
        $order1->setReference('10ABC')
            ->setStatus(1)
            ->setAmount(300)
            ->setCreatedAt(new \DateTime())
            ->setUser($user);


        $orderDetail1 = new OrderDetails();
        $orderDetail1->setQuantity(12)
            ->setUserOrder($order1)
            ->setProduct($productEBoutique2);

        $manager->persist($order1);
        $manager->persist($orderDetail1);
        $manager->flush();


    }
}
