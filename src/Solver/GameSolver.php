<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Solver;

use Benedekb97\Sudoku\Calculator\PossibilityCalculator;
use Benedekb97\Sudoku\Checker\GameSolveStateChecker;
use Benedekb97\Sudoku\Component\Game;
use Benedekb97\Sudoku\Guesser\CellNumberGuesser;

readonly class GameSolver
{
    public function __construct(
        private PossibilityCalculator $possibilityCalculator,
        private SinglePossibilitySolver $singlePossibilitySolver,
        private GameSolveStateChecker $solveStateChecker,
        private CellNumberGuesser $cellNumberGuesser,
    ) {}

    public function solve(Game $game): Game
    {
        $this->possibilityCalculator->calculate($game);

        $this->singlePossibilitySolver->solve($game);

        $retryCount = 0;

        while (!$this->solveStateChecker->check($game) && ++$retryCount < 5) {
            $this->possibilityCalculator->calculate($game, true);
            $this->singlePossibilitySolver->solve($game);
        }

        if (!$this->solveStateChecker->check($game)) {
            $game = $this->cellNumberGuesser->guess($game);
        }

        return $game;
    }
}