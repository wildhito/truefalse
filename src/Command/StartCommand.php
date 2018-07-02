<?php

namespace App\Command;

use App\Back\GameManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class StartCommand extends Command
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
        	->setName('tf:start')
            ->addArgument('ref', InputArgument::REQUIRED, 'Game reference.')
	        ->setDescription('Starts a game.')
	        ->setHelp('This command allows you to start a game')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ref = $input->getArgument('ref');

    	$game = $this->gameManager->loadGame($ref);
        $this->gameManager->start($game);

	    $output->writeln([
            'Question to ' . $game->getCurrentPlayer()->getName() . ':',
            $game->getCurrentQuestion()->getQuestion(),
	    ]);
    }
}
