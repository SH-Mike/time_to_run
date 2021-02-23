<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Outing;
use App\Entity\OutingType;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\OutingTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $userRepository;
    private $outingTypeRepository;
    private $slugify;
    private $faker;
    private $encoder;

    public function __construct(UserRepository $userRepository, OutingTypeRepository $outingTypeRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->outingTypeRepository = $outingTypeRepository;
        $this->slugify = new Slugify();
        $this->faker = Factory::create();
        $this->encoder = $encoder;
    }

    /**
     * We first load some random Users
     * We then load some random OutingTypes
     * Finaly, after creating Users and OutingTypes, we use them to load some random Outings
     * 
     * @param ObjectManager
     */
    public function load(ObjectManager $manager)
    {
        // Users loading
        $this->loadUsers($manager, 10);

        // OutingTypes loading
        $this->loadOutingTypes($manager, 5);

        // Outing loading
        $this->loadOutings($manager, 15, $this->userRepository, $this->outingTypeRepository);
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
            ->setSlug($this->slugify->slugify($user->getFirstName() . " " . $user->getLastName()))
            ->setPassword($this->encoder->encodePassword($user, 123123123));
            
            $manager->persist($user);
        }
        $manager->flush();
    }

    /**
     * Creates $outingTypesNb random outingTypes
     * 
     * @param ObjectManager $manager
     * @param int $outingTypesNb
     */
    public function loadOutingTypes($manager, $outingTypesNb){
        for ($i = 0; $i < $outingTypesNb; $i++){
            $outingType = new OutingType();
            $outingType->setTitle($this->faker->sentence(2));
            $manager->persist($outingType);
        }
        $manager->flush();
    }

    /**
     * Creates $outingNb random outings
     * 
     * @param ObjectManager $manager
     * @param int $outingNb
     * @param UserRepository $userRepository
     * @param OutingTypeRepository $outingTypeRepository
     */
    public function loadOutings($manager, $outingNb, $userRepository, $outingTypeRepository){
        $users = $userRepository->findAll();
        $outingTypes = $outingTypeRepository->findAll();
        for ($i = 0; $i < $outingNb; $i++){
            $outing = new Outing();
            $outing->setUser($users[rand(0, sizeof($users) - 1)])
            ->setOutingType($outingTypes[rand(0, sizeof($outingTypes) - 1)])
            ->setStartDate($this->faker->dateTimeBetween('yesterday', 'now'))
            ->setEndDate($this->faker->dateTimeBetween($outing->getStartDate()))
            ->setDistance($this->faker->randomFloat(1,0,50))
            ->setComment($this->faker->sentence())
            ;
            $manager->persist($outing);
        }
        $manager->flush();
    }
}
