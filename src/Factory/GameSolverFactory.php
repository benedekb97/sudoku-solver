<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Factory;

use Benedekb97\Sudoku\Calculator\PossibilityCalculator;
use Benedekb97\Sudoku\Checker\BoxExclusionChecker;
use Benedekb97\Sudoku\Checker\CellContainerPossibilityChecker;
use Benedekb97\Sudoku\Checker\CellPossibilityChecker;
use Benedekb97\Sudoku\Checker\GameSolveStateChecker;
use Benedekb97\Sudoku\Checker\PossibilityPairChecker;
use Benedekb97\Sudoku\Checker\SingleBoxPossibilityExclusionChecker;
use Benedekb97\Sudoku\Cloner\GameCloner;
use Benedekb97\Sudoku\Guesser\CellNumberGuesser;
use Benedekb97\Sudoku\Solver\GameSolver;
use Benedekb97\Sudoku\Solver\SinglePossibilitySolver;

class GameSolverFactory
{
    public function create(): GameSolver
    {
        return new GameSolver(
            $possibilityCalculator = new PossibilityCalculator(
                new CellPossibilityChecker(
                    new CellContainerPossibilityChecker()
                ),
                new BoxExclusionChecker(),
                new PossibilityPairChecker(),
                new SingleBoxPossibilityExclusionChecker()
            ),
            $singlePossibilitySolver = new SinglePossibilitySolver(),
            $solveStateChecker = new GameSolveStateChecker(),
            new CellNumberGuesser(
                new GameCloner(
                    new GameFactory()
                ),
                $possibilityCalculator,
                $singlePossibilitySolver,
                $solveStateChecker
            )
        );
    }
}