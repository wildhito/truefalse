<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Back\GameManager;

class WebhookController extends Controller
{
	private $gameManager;

	public function __construct(GameManager $gameManager)
	{
		$this->gameManager = $gameManager;
	}

    /**
     * @Route("/webhook")
     * @Route("/webhook/")
     */
    public function index(Request $request)
    {
    	$payload = json_decode($request->getContent(), true);

    	switch ($payload["queryResult"]["intent"]["displayName"]) {
			case "create-game":
				$response = $this->createGame($payload);
			break;

			default:
				$response =  [
		    		"fulfillmentText" => "Action introuvable.",
		    	];
    	}

    	array_merge($response, [
    		"source" => "example.com",
    	]);

        return new JsonResponse($response);
    }

    private function createGame($payload)
    {
    	$game = $this->gameManager->createGame($payload["queryResult"]["parameters"]["maxPoints"]);

    	return [
    		"outputContexts" => [
    			[
    				"name" => "gameContext",
    				"lifespanCount" => 1,
    				"parameters" => [
						"ref" =>$game->getReference(),
    				]
    			],
    		],
    		"fulfillmentText" => "Merci. Veuillez ajouter des joueurs.",
            "source" => "webhook",
            "fulfillmentMessages" => [],
    	];
    }
}
