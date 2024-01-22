<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Checker;

use Benedekb97\Sudoku\Component\Cell;

readonly class CellPossibilityChecker
{
    public function __construct(
        private CellContainerPossibilityChecker $cellContainerPossibilityChecker
    ) {}

    public function check(Cell $cell, int $number): bool
    {
        return
            $this->cellContainerPossibilityChecker->check($cell->getRow(), $cell, $number) &&
            $this->cellContainerPossibilityChecker->check($cell->getColumn(), $cell, $number) &&
            $this->cellContainerPossibilityChecker->check($cell->getBox(), $cell, $number);
    }
}