<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Guesser;

use Benedekb97\Sudoku\Calculator\PossibilityCalculator;
use Benedekb97\Sudoku\Checker\GameSolveStateChecker;
use Benedekb97\Sudoku\Cloner\GameCloner;
use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Component\Game;
use Benedekb97\Sudoku\Component\Guess;
use Benedekb97\Sudoku\Solver\SinglePossibilitySolver;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CellNumberGuesser
{
    public function __construct(
        private readonly GameCloner $cloner,
        private readonly PossibilityCalculator $possibilityCalculator,
        private readonly SinglePossibilitySolver $singlePossibilitySolver,
        private readonly GameSolveStateChecker $solveStateChecker,
        private Collection $invalidGuesses = new ArrayCollection(),
    ) {}

    public function guess(Game $game): Game
    {
        $newGame = $this->cloner->clone($game);

        try {
            $cell = $this->findOptimalCell($newGame);

            $number = $cell->getPossibilities()->first();

            $cell->setNumber($number);

            $retryCount = 0;

            while (!$this->solveStateChecker->check($newGame) && ++$retryCount < 5) {
                $this->possibilityCalculator->calculate($newGame);
                $this->singlePossibilitySolver->solve($newGame);
            }

            if (!$this->solveStateChecker->check($newGame)) {
                $newGame = $this->guess($newGame);
            }
        } catch (\InvalidArgumentException $exception) {
            if (isset($cell) && isset($number)) {
                $this->invalidGuesses->add(new Guess($cell, $number));

                $originalCell = $game->getCellByIndices(
                    $cell->getRow()->getNumber(),
                    $cell->getColumn()->getNumber()
                );

                $originalCell->removePossibility($number);

                $newGame = $this->guess($game);
            }
        }

        return $newGame;
    }

    private function findOptimalCell(Game $game): Cell
    {
        $cells = [];

        /** @var CellContainer $row */
        foreach ($game->getRows() as $row) {

            /** @var Cell $cell */
            foreach ($row->getCells() as $cell) {
                if (null !== $cell->getNumber()) {
                    continue;
                }

                $cells[] = [
                    'cell' => $cell,
                    'possibilityCount' => $cell->getPossibilities()->count(),
                ];
            }
        }

        uasort($cells, static function ($a, $b) {
            return $a['possibilityCount'] <=> $b['possibilityCount'];
        });

        return reset($cells)['cell'];
    }
}