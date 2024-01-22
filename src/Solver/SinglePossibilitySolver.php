<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Solver;

use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Component\Game;
use Doctrine\Common\Collections\ArrayCollection;

class SinglePossibilitySolver
{
    public function solve(Game $game): void
    {
        for ($number = 1; $number <= 9; $number++) {
            $this->solveRows($game, $number);
            $this->solveColumns($game, $number);
            $this->solveBoxes($game, $number);
        }
    }

    private function solveRows(Game $game, int $number): void
    {
        /** @var CellContainer $row */
        foreach ($game->getRows() as $row) {
            $foundInCells = new ArrayCollection();

            /** @var Cell $cell */
            foreach ($row->getCells() as $cell) {
                if ($cell->hasPossibility($number)) {
                    $foundInCells->add($cell);

                    if ($foundInCells->count() > 1) {
                        break;
                    }
                }
            }

            if ($foundInCells->count() === 1) {
                $foundInCells->first()->setNumber($number);
            }
        }
    }

    private function solveColumns(Game $game, int $number): void
    {
        /** @var CellContainer $column */
        foreach ($game->getColumns() as $column) {
            $foundInCells = new ArrayCollection();

            /** @var Cell $cell */
            foreach ($column->getCells() as $cell) {
                if ($cell->hasPossibility($number)) {
                    $foundInCells->add($cell);

                    if ($foundInCells->count() > 1) {
                        break;
                    }
                }
            }

            if ($foundInCells->count() === 1) {
                $foundInCells->first()->setNumber($number);
            }
        }
    }

    private function solveBoxes(Game $game, int $number): void
    {
        /** @var CellContainer $box */
        foreach ($game->getBoxes() as $box) {
            $foundInCells = new ArrayCollection();

            /** @var Cell $cell */
            foreach ($box->getCells() as $cell) {
                if ($cell->hasPossibility($number)) {
                    $foundInCells->add($cell);

                    if ($foundInCells->count() > 1) {
                        break;
                    }
                }
            }

            if ($foundInCells->count() === 1) {
                $foundInCells->first()->setNumber($number);
            }
        }
    }
}