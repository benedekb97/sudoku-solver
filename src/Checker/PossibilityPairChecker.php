<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Checker;

use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Component\Game;
use Doctrine\Common\Collections\Collection;

class PossibilityPairChecker
{
    public function check(Game $game): void
    {
        $this->checkContainers($game->getColumns());
        $this->checkContainers($game->getRows());
        $this->checkContainers($game->getBoxes());
    }

    private function checkContainers(Collection $cellContainers): void
    {
        /** @var CellContainer $cellContainer */
        foreach ($cellContainers as $cellContainer) {
            /** @var Cell $cell */
            foreach ($cellContainer->getCells() as $cell) {
                if ($cell->getPossibilities()->count() === 2) {
                    $this->checkOtherCells($cell, $cellContainer);
                }
            }
        }
    }

    private function checkOtherCells(Cell $cell, CellContainer $container): void
    {
        $secondCell = null;

        /** @var Cell $otherCell */
        foreach ($container->getCells() as $otherCell) {
            if ($otherCell === $cell) {
                continue;
            }

            if (2 !== $otherCell->getPossibilities()->count()) {
                continue;
            }

            $continue = false;

            foreach ($otherCell->getPossibilities() as $possibility) {
                if (!$cell->getPossibilities()->contains($possibility)) {
                    $continue = true;

                    break;
                }
            }

            if ($continue) {
                continue;
            }

            foreach ($cell->getPossibilities() as $possibility) {
                if (!$otherCell->getPossibilities()->contains($possibility)) {
                    $continue = true;

                    break;
                }
            }

            if ($continue) {
                continue;
            }

            $secondCell = $otherCell;

            break;
        }

        if (null === $secondCell) {
            return;
        }

        /** @var Cell $otherCell */
        foreach ($container->getCells() as $otherCell) {
            if ($cell === $otherCell || $secondCell === $otherCell) {
                continue;
            }

            foreach ($cell->getPossibilities() as $possibility) {
                $otherCell->removePossibility($possibility);
            }
        }
    }
}