<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Checker;

use Benedekb97\Sudoku\Component\Box;
use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\Game;

class GameSolveStateChecker
{
    public function check(Game $game): bool
    {
        /** @var Box $box */
        foreach ($game->getBoxes() as $box) {
            /** @var Cell $cell */
            foreach ($box->getCells() as $cell) {
                if (null === $cell->getNumber()) {
                    return false;
                }
            }
        }

        return true;
    }
}