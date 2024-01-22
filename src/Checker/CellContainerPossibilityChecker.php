<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Checker;

use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;

class CellContainerPossibilityChecker
{
    public function check(CellContainer $cellContainer, Cell $cell, int $number): bool
    {
        /** @var Cell $cellCheck */
        foreach ($cellContainer->getCells() as $cellCheck) {
            if ($cellCheck === $cell) {
                continue;
            }

            if ($number === $cellCheck->getNumber()) {
                return false;
            }
        }

        return true;
    }
}