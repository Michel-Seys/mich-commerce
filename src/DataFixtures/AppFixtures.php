<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Liior\Faker\Prices;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $admin = new User();
        $admin  ->setFullName("admin")
                ->setPassword($this->encoder->encodePassword($admin,"password"))
                ->setEmail("admin@email.com")
                ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $user   ->setEmail("user$u@email.com")
                    ->setFullName($faker->name())
                    ->setPassword($this->encoder->encodePassword($user, "password"));

            $manager->persist($user);
        }

        for ($c = 0; $c < 3; $c++) {
            $category = new Category();
            $category->setName($faker->word())
                    ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(3, 14); $p++) {
                $product = new Product();
                $product->setName($faker->sentence())
                        ->setSlug(strtolower($this->slugger->slug($product->getName())))
                        ->setPrice($faker->price(4000, 20000))
                        ->setCategory($category)
                        ->setShortDescription($faker->paragraph())
                        ->setMainPicture($faker->imageUrl(400, 400, true));

                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
