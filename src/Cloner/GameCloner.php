<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Cloner;

use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Component\Game;
use Benedekb97\Sudoku\Factory\GameFactory;

readonly class GameCloner
{
    public function __construct(
        private GameFactory $gameFactory
    ) {}

    public function clone(Game $game): Game
    {
        $newGame = $this->gameFactory->createGame();

        /** @var CellContainer $row */
        foreach ($game->getRows() as $row) {

            /** @var Cell $cell */
            foreach ($row->getCells() as $cell) {
                $newCell = $newGame->getCellByIndices($row->getNumber(), $cell->getColumn()->getNumber());

                $newCell->setNumber($cell->getNumber())
                    ->setOriginal($cell->isOriginal());

                foreach ($cell->getPossibilities() as $possibility) {
                    $newCell->addPossibility($possibility);
                }
            }
        }

        return $newGame;
    }
}