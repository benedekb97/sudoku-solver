<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Checker;

use Benedekb97\Sudoku\Component\Box;
use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Doctrine\Common\Collections\ArrayCollection;

class BoxExclusionChecker
{
    public function check(Box $box, int $number): ?CellContainer
    {
        return $this->checkColumns($box, $number) ?? $this->checkRows($box, $number);
    }

    private function checkColumns(Box $box, int $number): ?CellContainer
    {
        $foundInColumns = new ArrayCollection();

        /** @var Cell $cell */
        foreach ($box->getCells() as $cell) {
            if ($cell->hasPossibility($number) && !$foundInColumns->contains($cell->getColumn())) {
                $foundInColumns->add($cell->getColumn());
            }
        }

        return 1 === $foundInColumns->count() ? $foundInColumns->first() : null;
    }

    private function checkRows(Box $box, int $number): ?CellContainer
    {
        $foundInRows = new ArrayCollection();

        /** @var Cell $cell */
        foreach ($box->getCells() as $cell) {
            if ($cell->hasPossibility($number) && !$foundInRows->contains($cell->getRow())) {
                $foundInRows->add($cell->getRow());
            }
        }

        return 1 === $foundInRows->count() ? $foundInRows->first() : null;
    }
}