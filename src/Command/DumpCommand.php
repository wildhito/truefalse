<?php

namespace App\Command;

use App\Back\GameManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DumpCommand extends Command
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
        	->setName('tf:dump')
            ->addArgument('ref', InputArgument::REQUIRED, 'Game reference.')
	        ->setDescription('Dumps a game.')
	        ->setHelp('This command allows you to dump a game')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ref = $input->getArgument('ref');

    	$game = $this->gameManager->loadGame($ref);

	    $output->writeln([
	        'Reference: ' . $game->getReference(),
            'Max points: ' . $game->getMaxPoints(),
            'State: ' . $game->getState(),
	    ]);

        $output->write("Players: ");
        $players = $game->getPlayers();
        foreach ($players as $player) {
            $output->write($player->getName() . " [" . $player->getScore() . "], ");
        }

        $output->writeln([
            '',
            'Current turn: ' . $game->getCurrentTurn(),
        ]);

        if ($game->getCurrentPlayer()) {
            $output->writeln([
                'Current player: ' . $game->getCurrentPlayer()->getName(),
            ]);
        }

        if ($game->getCurrentQuestion()) {
            $output->writeln([
                'Current question: ' . $game->getCurrentQuestion()->getQuestion(),
            ]);
        }

    }
}
