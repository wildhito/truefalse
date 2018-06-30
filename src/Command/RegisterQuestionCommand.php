<?php

namespace App\Command;

use App\Back\QuestionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class RegisterQuestionCommand extends Command
{
	private $questionManager;

    public function __construct(QuestionManager $questionManager)
    {
        $this->questionManager = $questionManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        	->setName('tf:add-question')
            ->addArgument('question', InputArgument::REQUIRED, 'The question sentence.')
            ->addArgument('answer', InputArgument::REQUIRED, 'The question answer.')
	        ->setDescription('Registers a question.')
	        ->setHelp('This command allows you to register a new question')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $question = $input->getArgument('question');
        $answer = in_array(
            strtolower($input->getArgument('answer')),
            [ 'y', 'yes', 'true', 't', 'o', 'oui', 'v', 'vrai', '1' ], true
        );

        $this->questionManager->createQuestion($question, $answer);

	    $output->writeln([
	        'Registered question.',
	    ]);
    }
}
