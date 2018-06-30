<?php

namespace App\Back;

class GameException extends \Exception
{
    const GAME_NOT_FOUND              = 4001;
    const RANDOM_QUESTION_UNAVAILABLE = 4002;
    const GAME_NOT_STARTABLE          = 4003;
}
