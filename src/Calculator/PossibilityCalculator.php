<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Calculator;

use Benedekb97\Sudoku\Checker\BoxExclusionChecker;
use Benedekb97\Sudoku\Checker\CellPossibilityChecker;
use Benedekb97\Sudoku\Checker\PossibilityPairChecker;
use Benedekb97\Sudoku\Checker\SingleBoxPossibilityExclusionChecker;
use Benedekb97\Sudoku\Component\Box;
use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\Game;

readonly class PossibilityCalculator
{
    public function __construct(
        private CellPossibilityChecker $cellPossibilityChecker,
        private BoxExclusionChecker $boxExclusionChecker,
        private PossibilityPairChecker $possibilityPairChecker,
        private SingleBoxPossibilityExclusionChecker $singleBoxPossibilityExclusionChecker
    ) {}

    public function calculate(Game $game, bool $remove = false): void
    {
        for ($number = 1; $number <= 9; $number++) {
            /** @var Box $box */
            foreach ($game->getBoxes() as $box) {
                /** @var Cell $cell */
                foreach ($box->getCells() as $cell) {
                    if (null !== $cell->getNumber()) {
                        continue;
                    }

                    if ($this->cellPossibilityChecker->check($cell, $number)) {
                        $cell->addPossibility($number);
                    } else {
                        $cell->removePossibility($number, $remove);
                    }
                }
            }
        }

        $this->narrowDownPossibilities($game);

        $this->possibilityPairChecker->check($game);
        $this->singleBoxPossibilityExclusionChecker->check($game);
    }

    private function narrowDownPossibilities(Game $game): void
    {
        for ($number = 1; $number <= 9; $number++) {
            /** @var Box $box */
            foreach ($game->getBoxes() as $box) {
                $cellContainer = $this->boxExclusionChecker->check($box, $number);

                if (null === $cellContainer) {
                    continue;
                }

                /** @var Cell $cell */
                foreach ($cellContainer->getCells() as $cell) {
                    if ($cell->getBox() === $box) {
                        continue;
                    }

                    $cell->removePossibility($number);
                }
            }
        }
    }
}