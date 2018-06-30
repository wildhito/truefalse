<?php

namespace App\Back;

use App\Entity\Game;
use Doctrine\ORM\EntityManager;
use App\Repository\GameRepository;
use App\Repository\QuestionRepository;
use App\Back\GameException;

class GameManager
{
    private $em;
    private $gameRepo;
    private $questionRepo;

    public function __construct(EntityManager $em, GameRepository $gameRepo, QuestionRepository $questionRepo)
    {
        $this->em = $em;
        $this->gameRepo = $gameRepo;
        $this->questionRepo = $questionRepo;
    }

    public function createGame(int $maxPoints = 10)
    {
        $newGame = new Game();
        $newGame->setCurrentTurn(0);
        $newGame->setMaxPoints($maxPoints);
        $newGame->setReference(uniqid());
        $newGame->setState(Game::STATE_NOT_STARTED);

        $this->em->persist($newGame);
        $this->em->flush();
    }

    /**
     * @throws GameException
     */
    public function loadGame(string $reference): Game
    {
        $game = $this->gameRepo->findOneBy([ 'reference' => $reference ]);
        if (!$game) {
            throw new GameException("Failed to load game from repository", GameException::GAME_NOT_FOUND);
        }
        return $game;
    }

    public function registerPlayer(Game $game, $name)
    {
        $newPlayer = new Player();
        $newPlayer->setName($name);
        $newPlayer->setScore(0);
        $game->addPlayer($newPlayer);

        $this->em->persist($newPlayer);
        $this->em->persist($game);
        $this->em->flush();
    }

    /**
     * @throws GameException
     */
    public function play(Game $game, bool $playerAnswer)
    {
        if (!$game->started()) {
            $this->startToPlay();
        } else {
            $this->continuePlaying($game, $playerAnswer);
        }
    }

    /**
     * @throws GameException
     */
    private function startToPlay(Game $game)
    {
        if (!$game->canStart()) {
            throw new GameException("Game cannot be started", GameException::GAME_NOT_STARTABLE);
        }

        $game->start();
        $question = $this->pickRandomQuestion($game);
        $game->setCurrentQuestion($question);
        $game->addPlayedQuestion($question);

        $this->em->persist($game);
        $this->em->flush();
    }

    /**
     * @throws GameException
     */
    private function continuePlaying(Game $game, bool $playerAnswer)
    {
        $playerSuccess = $this->tryAnswer($game->getCurrentQuestion(), $playerAnswer);

        if ($playerSuccess) {
            $player = $game->getCurrentPlayer();
            $player->incScore();

            $this->em->persist($player);
            $this->em->flush();
        }

        if ($player->getScore() >= $game->getMaxPoints()) {
            $game->finish();
        } else {
            $this->nextQuestion($game);
        }

        $this->em->persist($game);
        $this->em->flush();
    }

    /**
     * @throws GameException
     */
    private function tryAnswer(Question $question, bool $playerAnswer): bool
    {
        return ($playerAnswer === $question->getAnswer());
    }

    /**
     * @throws GameException
     */
    private function nextQuestion(Game $game): bool
    {
        if (($nextPlayer = $game->getNextPlayer()) !== null) {
            $game->setCurrentPlayer($nextPlayer);
        } else {
            $game->nextTurn();
        }

        $question = $this->pickRandomQuestion($game);
        $game->setCurrentQuestion($question);
        $game->addPlayedQuestion($question);
    }

    /**
     * @throws GameException
     */
    private function pickRandomQuestion(Game $game): Question
    {
        $playedQuestions = $game->getPlayedQuestions();
        $playedQuestionIds = [];
        foreach ($playedQuestions as $question) {
            $playedQuestionIds[] = $question->getId();
        }
        
        $randomQuestion = $this->questionRepo->pickRandomQuestion($playedQuestionIds);
        if (!$randomQuestion) {
            throw new GameException("No more random question available", GameException::RANDOM_QUESTION_UNAVAILABLE);
        }
        return $randomQuestion;
    }
}
