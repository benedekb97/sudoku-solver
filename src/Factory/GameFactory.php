<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Factory;

use Benedekb97\Sudoku\Component\Box;
use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Component\Game;

class GameFactory
{
    public function createGame(): Game
    {
        $game = new Game();

        $this->createColumns($game);
        $this->createRows($game);
        $this->createBoxes($game);
        $this->createCells($game);

        return $game;
    }

    private function createCells(Game $game): void
    {
        for ($rowIndex = 0; $rowIndex < 9; $rowIndex++) {
            for ($columnIndex = 0; $columnIndex < 9; $columnIndex++) {
                $cell = new Cell();

                $column = $game->getColumnByIndex($columnIndex)->addCell($cell);
                $row = $game->getRowByIndex($rowIndex)->addCell($cell);
                $box = $game->getBoxByCellIndex($rowIndex, $columnIndex)->addCell($cell);

                $cell->setColumn($column);
                $cell->setRow($row);
                $cell->setBox($box);
            }
        }
    }

    private function createColumns(Game $game): void
    {
        for ($columnIndex = 0; $columnIndex < 9; $columnIndex++) {
            $game->addColumn(
                new CellContainer(number: $columnIndex)
            );
        }
    }

    private function createRows(Game $game): void
    {
        for ($rowIndex = 0; $rowIndex < 9; $rowIndex++) {
            $game->addRow(
                new CellContainer(number: $rowIndex)
            );
        }
    }

    private function createBoxes(Game $game): void
    {
        for ($verticalIndex = 0; $verticalIndex < 3; $verticalIndex++) {
            for ($horizontalIndex = 0; $horizontalIndex < 3; $horizontalIndex++) {
                $game->addBox(
                    new Box(verticalIndex: $verticalIndex, horizontalIndex: $horizontalIndex)
                );
            }
        }
    }
}