<?php

namespace App\Command;

use App\Back\GameManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class NewGameCommand extends Command
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
        	->setName('tf:new-game')
        	->addArgument('maxPoints', InputArgument::REQUIRED, 'The max count of points.')
	        ->setDescription('Creates a new game.')
	        ->setHelp('This command allows you to create a new game with a max count of points')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$maxPoints = $input->getArgument('maxPoints');

    	$game = $this->gameManager->createGame($maxPoints);

	    $output->writeln([
	        'Created a new Game in ' . $maxPoints . ' points',
	        'Keep this reference to play: ' . $game->getReference(),
	    ]);
    }
}
