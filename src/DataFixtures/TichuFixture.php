<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Round;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class TichuFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $players = [
            'Marco',
            'Robin',
            'Yann',
            'Yannick',
            'Nora',
            'Jeannine',
            'Lisa',
        ];

        $playerObjects = [];

        foreach ($players as $index => $playerName) {
            $player = new Player();
            $player->setName($playerName);
            $manager->persist($player);
            $playerObjects[] = $player;
        }


        $this->createGame($manager, $playerObjects);
        $this->createGame($manager, [$playerObjects[1],$playerObjects[5],$playerObjects[6],$playerObjects[4]]);
        $this->createGame($manager, [$playerObjects[5],$playerObjects[4],$playerObjects[6],$playerObjects[2]]);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param array $playerObjects
     * @throws \Exception
     */
    public function createGame(ObjectManager $manager, array $playerObjects): void
    {
        $game = new Game();
        $game->setPlayerOneTeamA($playerObjects[0]);
        $game->setPlayerTwoTeamA($playerObjects[1]);
        $game->setPlayerOneTeamB($playerObjects[2]);
        $game->setPlayerTwoTeamB($playerObjects[3]);

        $manager->persist($game);
        for ($i = 0; $i < 8; $i++) {
            $round = new Round();
            $round->setGame($game);

            $points = rand(-5, 25) * 5;
            $round->setPointsTeamA($points);
            $round->setPointsTeamB(100 - $points);
            $round->setFinishTime(new \DateTime());

            $manager->persist($round);
        }
    }
}
