<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 2019-05-16
 * Time: 13:34
 */

namespace App\Controller;


use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Round;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class TichuController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Game::class);

        $games = $repository->findBy([], array('id' => 'DESC'));

        return $this->render('overview.html.twig', [
            'games' => $games
        ]);
    }

    /**
     * @Route("/statistics")
     */
    public function statistics()
    {
        $sql = "select name, sum(t.points) as points from ( select name, sum(IFNULL(r.points_team_a, 0)) as points
from player
       left join game p1ta on player.id = p1ta.player_one_team_a_id
       left join round r on p1ta.id = r.game_id
group by name
union

select name, sum(IFNULL(r.points_team_a, 0)) as points
from player
       left join game p2ta on player.id = p2ta.player_two_team_a_id
       left join round r on p2ta.id = r.game_id
group by name

union

select name,
       sum(IFNULL(r.points_team_b, 0)) as points
from player
       left join game p1tb on player.id = p1tb.player_one_team_b_id
       left join round r on p1tb.id = r.game_id
group by name

union


select name,
       sum(IFNULL(r.points_team_b, 0)) as points
from player
       left join game p2tb on player.id = p2tb.player_two_team_b_id
       left join round r on p2tb.id = r.game_id
group by name) as t group by name;";

        $stmt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $labels = [];
        $data = [];

        foreach ($result as $index => $item) {
            $labels[] = $item['name'];
            $data[] = $item['points'];
        }


        return $this->render('statistics.html.twig', [
            'labels' => $labels,
            'data' => $data
        ]);
    }

    /**
     * @Route("/add")
     */
    public function add(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Player::class);

        if ($request->getMethod() == 'POST') {
            $teama = $request->get('teama');
            $teamb = $request->get('teamb');

            if (count($teama) == 2 && count($teamb) == 2) {

                $game = new Game();

                $game->setPlayerOneTeamA($repository->find($teama[0]));
                $game->setPlayerTwoTeamA($repository->find($teama[1]));
                $game->setPlayerOneTeamB($repository->find($teamb[0]));
                $game->setPlayerTwoTeamB($repository->find($teamb[1]));
                $this->getDoctrine()->getManager()->persist($game);
                $this->getDoctrine()->getManager()->flush();
            }

            return new RedirectResponse("/detail/" . $game->getId());

        }


        $repository = $this->getDoctrine()->getRepository(Player::class);

        $players = $repository->findBy([], array('id' => 'ASC'));


        return $this->render('add.html.twig', [
            'players' => $players
        ]);
    }

    /**
     * @Route("/detail/{id}/deletelastround")
     */
    public function deletelastround($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Game::class);

        $game = $repository->find($id);
        $rounds = $game->getRounds();
        $lastRound = $rounds[count($rounds) - 1];

        $this->getDoctrine()->getManager()->remove($lastRound);
        $this->getDoctrine()->getManager()->flush();
        return new RedirectResponse("/detail/" . $game->getId());

    }


    /**
     * @Route("/detail/{id}/delete")
     */
    public function delete($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Game::class);

        $game = $repository->find($id);
        $this->getDoctrine()->getManager()->remove($game);
        $this->getDoctrine()->getManager()->flush();
        return new RedirectResponse("/");

    }


    /**
     * @Route("/detail/{id}")
     */
    public function detail($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Game::class);

        $game = $repository->find($id);

        if ($request->getMethod() == 'POST') {

            $teama = $request->get('teama');
            $teamb = $request->get('teamb');
            $doublea = $request->get('doublea');
            $doubleb = $request->get('doubleb');
            $tichua = $request->get('tichua');
            $tichub = $request->get('tichub');
            $gtichua = $request->get('gtichua');
            $gtichub = $request->get('gtichub');
            $ntichua = $request->get('ntichua');
            $ntichub = $request->get('ntichub');
            $ngtichua = $request->get('ngtichua');
            $ngtichub = $request->get('ngtichub');

            $total = intval($teama) + intval($teamb);
            if ($total % 100 == 0) {
                if ($doublea) {
                    $teama = 200;
                    $teamb = 0;
                }
                if ($doubleb) {
                    $teamb = 200;
                    $teama = 0;
                }
                if ($tichua) {
                    $teama += 100;
                }
                if ($tichub) {
                    $teamb += 100;
                }
                if ($gtichua) {
                    $teama += 200;
                }
                if ($gtichub) {
                    $teamb += 200;
                }

                if ($ntichua) {
                    $teama -= 100;
                }
                if ($ntichub) {
                    $teamb -= 100;
                }
                if ($ngtichua) {
                    $teama -= 200;
                }
                if ($ngtichub) {
                    $teamb -= 200;
                }

                $round = new Round();
                $round->setGame($game);
                $round->setPointsTeamA($teama);
                $round->setPointsTeamB($teamb);
                $round->setFinishTime(new \DateTime());

                $this->getDoctrine()->getManager()->persist($round);
                $this->getDoctrine()->getManager()->flush();
            }

        }


        return $this->render('detail.html.twig', [
            'game' => $game
        ]);

    }


}
