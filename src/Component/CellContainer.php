<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Component;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CellContainer
{
    public function __construct(
        private ?int $number = null,
        private Collection $cells = new ArrayCollection()
    ) {}

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCells(): Collection
    {
        return $this->cells;
    }

    public function hasCell(Cell $cell): bool
    {
        return $this->cells->contains($cell);
    }

    public function addCell(Cell $cell): self
    {
        if (!$this->hasCell($cell)) {
            $this->cells->add($cell);
        }

        return $this;
    }

    public function hasNumber(int $number): bool
    {
        return !$this->cells->filter(static fn (Cell $cell) => $cell->getNumber() === $number)->isEmpty();
    }
}