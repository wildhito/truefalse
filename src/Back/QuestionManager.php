<?php

namespace App\Back;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\QuestionRepository;
use App\Entity\Question;

class QuestionManager
{
    private $em;
    private $questionRepo;

    public function __construct(EntityManagerInterface $em, QuestionRepository $questionRepo)
    {
        $this->em = $em;
        $this->questionRepo = $questionRepo;
    }

    public function createQuestion(string $question, bool $answer)
    {
        $newQuestion = new Question();
        $newQuestion->setQuestion($question);
        $newQuestion->setAnswer($answer);

        $this->em->persist($newQuestion);
        $this->em->flush();
    }

}
