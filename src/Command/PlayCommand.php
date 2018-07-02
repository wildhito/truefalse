<?php

namespace App\Command;

use App\Back\GameManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PlayCommand extends Command
{
	private $gameManager;

    public function __construct(GameManager $gameManager)
    {
        $this->gameManager = $gameManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        	->setName('tf:play')
            ->addArgument('ref', InputArgument::REQUIRED, 'Game reference.')
            ->addArgument('answer', InputArgument::REQUIRED, 'Question answer.')
	        ->setDescription('Starts a game.')
	        ->setHelp('This command allows you to play to a game')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ref = $input->getArgument('ref');
        $answer = in_array(
            strtolower($input->getArgument('answer')),
            [ 'y', 'yes', 'true', 't', 'o', 'oui', 'v', 'vrai', '1' ], true
        );

    	$game = $this->gameManager->loadGame($ref);
        $success = $this->gameManager->play($game, $answer);

        if ($success) {
            $output->writeln("Right answer");    
        } else {
            $output->writeln("Wrong answer");
        }

        if ($game->finished()) {
            $output->writeln([
                "Game finished, thanks for playing.",
                "Score board:",
            ]);
            $players = $game->getPlayers();
            foreach ($players as $player) {
                $output->writeln($player->getName() . ": " . $player->getScore());
            }
        } else {
            $output->writeln([
                'Question to ' . $game->getCurrentPlayer()->getName() . ':',
                $game->getCurrentQuestion()->getQuestion(),
            ]);
        }
    }
}
