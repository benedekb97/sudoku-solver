<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Checker;

use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Component\Game;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SingleBoxPossibilityExclusionChecker
{
    public function check(Game $game): void
    {
        $this->checkCellContainers($game->getRows());
        $this->checkCellContainers($game->getColumns());
    }

    private function checkCellContainers(Collection $cellContainers): void
    {
        for ($number = 1; $number <= 9; $number++) {
            /** @var CellContainer $cellContainer */
            foreach($cellContainers as $cellContainer) {
                if ($cellContainer->hasNumber($number)) {
                    continue;
                }

                $foundInBoxes = new ArrayCollection();

                /** @var Cell $cell */
                foreach ($cellContainer->getCells() as $cell) {
                    if ($cell->hasPossibility($number) && !$foundInBoxes->contains($cell->getBox())) {
                        $foundInBoxes->add($cell->getBox());
                    }

                    if ($foundInBoxes->count() > 1) {
                        break;
                    }
                }

                if ($foundInBoxes->count() === 1) {
                    /** @var Cell $cell */
                    foreach ($foundInBoxes->first()->getCells() as $cell) {
                        if ($cellContainer->hasCell($cell)) {
                            continue;
                        }

                        $cell->removePossibility($number);
                    }
                }
            }
        }
    }
}