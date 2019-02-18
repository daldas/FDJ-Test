<?php
namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class FdjController extends ApiController
{
    /**
     * @Route("/teams/{strLeague}", methods="GET")
     */
    public function getTeams(Request $request,$strLeague)
    {
        $query = array('l' => $strLeague);

        $url = 'https://www.thesportsdb.com/api/v1/json/1/search_all_teams.php';
        $response = $this->getApiData($url,$query);

        $equipes=array();
        foreach ($response as $team)
        {

            foreach($team as $equipe)
            {
                $equipes[] = array('id'=>$equipe['idTeam'],'logo' => $equipe['strTeamBadge']);
            }
        }
        return $this->respond($equipes);
    }
    /**
     * @Route("/leagues",  methods="GET")
     */
    public function getLeagues(Request $request)
    {
        $url ='https://www.thesportsdb.com/api/v1/json/1/all_leagues.php';

        $response = $this->getApiData($url);

        $my_leagues=array();
        foreach ($response as $league)
        {

            foreach($league as $my_league)
            {
                $my_leagues[] = array(
                    'id' => $my_league['idLeague'],
                    'league' => $my_league['strLeague']
                );
            }
        }

        return $this->respond($my_leagues);
    }

    /**
     * @Route("/players/{id}", methods="GET")
     */
    public function getPlayers(Request $request,$id)
    {

        $query = array('id' => $id);

        $url = 'https://www.thesportsdb.com/api/v1/json/1/lookup_all_players.php';
        $response = $this->getApiData($url,$query);

        $joueurs=array();
        foreach ($response as $player)
        {

            foreach($player as $joueur)
            {
                $joueurs[] = array(
                    'id' => $joueur['idPlayer'],
                    'image' => $joueur['strThumb'],
                    'name' => $joueur['strPlayer'],
                    'poste' => $joueur['strPosition'],
                    'birthday' => $joueur['dateBorn'],
                    'price' => $joueur['strSigning'],
                );
            }
        }

        return $this->respond($joueurs);
    }

}
