<?php

namespace App\Command;

use App\Back\QuestionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class RegisterQuestionsFromFileCommand extends Command
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
        	->setName('tf:import-questions')
            ->addArgument('filepath', InputArgument::REQUIRED, 'The filepath.')
            ->addArgument('answer', InputArgument::REQUIRED, 'The answer.')
	        ->setDescription('Registers questions.')
	        ->setHelp('This command allows you to import new questions')
    	;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filepath = $input->getArgument('filepath');
        $answer = in_array(
            strtolower($input->getArgument('answer')),
            [ 'y', 'yes', 'true', 't', 'o', 'oui', 'v', 'vrai', '1' ], true
        );

        $handle = fopen($filepath, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $this->questionManager->createQuestion($line, $answer);
                $output->writeln($answer ? $line . ' (true)' : $line . ' (false)');
            }

            fclose($handle);
        } else {
            throw new \Exception("Could not open file $filepath");
        }
    }
}
