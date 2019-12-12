<?php

namespace App\DataFixtures;

use App\Entity\Commentaries;
use App\Entity\Picture;
use App\Entity\Tricks;
use App\Entity\TypeTricks;
use App\Entity\Users;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('FR-fr');

        $users = [];
        $categories = [];
        $genders = ['male', 'female'];
        $categoriesDemoName = ['Grabs', 'Rotations', 'Flips', 'Rotations désaxées', 'Slides', 'One foot', 'Old school'];
        $tricksDemoName = ['Mute', 'Indy', '360', '720', 'Backflip', 'Misty', 'Tail slide', 'Method air', 'Backside air'];

        // 20 User
        for ($i=0; $i<20; $i++)
        {
            $user = new Users();

            $gender = $faker->randomElement($genders);

            $user->setUsername($faker->userName)
                ->setEmail($faker->safeEmail)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setImage('https://randomuser.me/api/portraits/' . ($gender == 'male' ? 'men/' : 'women/') . $faker->numberBetween(1,99) . '.jpg')
                ->setLevelAdministration((array)'ROLE_USER');
            $manager->persist($user);
            $users[] = $user;
        }

        // 7 Category
        foreach ($categoriesDemoName as $categoryName)
        {
            $category = new TypeTricks();
            $category->setNameTricks($categoryName);

            $manager->persist($category);
            $categories[] = $category;
        }

        // 9 Tricks
        foreach ($tricksDemoName as $trickName)
        {
            $trick = new Tricks();
            $trick->setName($trickName)
                ->setDescription($faker->paragraph(5))
                ->setCreateAt(new \Datetime)
                ->setUsers($faker->randomElement($users))
                ->setTypeTricks($faker->randomElement($categories));

            // 3 Image by Trick
            for ($k=1; $k<4; $k++)
            {
                $image = new Picture();
                $image->setUrl('/img/tricks/default/default.png')
                    ->setTricks($trick);

                $manager->persist($image);

                if($k === 3)
                {
                    // Last Image become the main one
                    $trick->setMainPicture($image);
                    $manager->persist($trick);
                }

            }

            // 1 to 2 Video by Trick
            for ($l=0; $l<mt_rand(1, 2); $l++)
            {
                $video = new Video();
                $video->setUrl('https://www.youtube.com/embed/v1mI6KRkaQo')
                    ->setTricks($trick);

                $manager->persist($video);
            }

            // 0 to 30 Comment by Trick
            for ($m=0; $m<mt_rand(0, 30); $m++)
            {
                $comment = new Commentaries();
                $comment->setCommentarie($faker->sentence(mt_rand(1, 5)))
                    ->setCreateAt(new \Datetime)
                    ->setUsers($faker->randomElement($users))
                    ->setTricks($trick);

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
