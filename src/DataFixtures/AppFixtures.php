<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $userRepository;
    private $slugify;
    private $faker;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->slugify = new Slugify();
        $this->faker = Factory::create();
    }

    /**
     * We first load some random Users
     * 
     * @param ObjectManager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager, 10);

        $manager->flush();
    }

    /**
     * Creates $usersNb random Users
     * 
     * @param ObjectManager $manager
     * @param int $usersNb
     */
    public function loadUsers($manager, $usersNb){
        for ($i = 0; $i < $usersNb; $i++){
            $user = new User();
            $user->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setBirthDate($this->faker->dateTime('now'))
            ->setEmail($this->faker->email())
            ->setSlug($this->slugify->slugify($user->getFirstName() . " " . $user->getLastName()));
            
            $manager->persist($user);
        }
        $manager->flush();
    }
}
