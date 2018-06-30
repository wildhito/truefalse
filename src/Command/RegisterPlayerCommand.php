<?php

namespace App\Command;

use App\Back\GameManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class RegisterPlayerCommand extends Command
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
        	->setName('tf:add-player')
            ->addArgument('ref', InputArgument::REQUIRED, 'Game reference.')
            ->addArgument('name', InputArgument::REQUIRED, 'The player name.')
	        ->setDescription('Registers a player.')
	        ->setHelp('This command allows you to register a named player')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ref = $input->getArgument('ref');
        $name = $input->getArgument('name');

    	$game = $this->gameManager->loadGame($ref);
        
        $this->gameManager->registerPlayer($game, $name);

	    $output->writeln([
	        'Registered player ' . $name . ' in game ' . $ref,
	    ]);
    }
}
