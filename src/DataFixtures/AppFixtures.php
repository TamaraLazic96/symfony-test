<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const LANGUAGES = [
        'en',
        'fr'
    ];
    private $userPasswordEncoder;

    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER],
        ],
        [
            'username' => 'supperAdmin',
            'email' => 'supperAdmin@gold.com',
            'password' => 'supperAdmin1234',
            'fullName' => 'Micro SupperAdmin',
            'roles' => [User::ROLE_ADMIN],
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPost($manager);
    }

    private function loadMicroPost(\Doctrine\Persistence\ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(self::POST_TEXT[rand(0, count(self::POST_TEXT) - 1)]);
            $date = new \DateTime();
            $date->modify('-'.rand(0, 10).' day');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0, count(self::USERS) - 1)]['username']
            ));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    private function loadUsers(\Doctrine\Persistence\ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->userPasswordEncoder->encodePassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);

            $this->addReference($userData['username'], $user);

            $preferences = new UserPreferences();
            $preferences->setLocal(self::LANGUAGES[rand(0, 1)]);

            $user->setUserPreferences($preferences);

            //$manager->persist($preferences); // two options - persist on preferences
            // instead of persisting it we can modify user entity to have cascade on persist
            $manager->persist($user);
        }
        $manager->flush();
    }
}
