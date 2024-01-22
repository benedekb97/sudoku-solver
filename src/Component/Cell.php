<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Component;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cell
{
    private CellContainer $column;

    private CellContainer $row;

    private Box $box;

    private bool $original = false;

    public function __construct(
        private ?int $number = null,
        private Collection $possibilities = new ArrayCollection(),
    ) {}

    public function getColumn(): CellContainer
    {
        return $this->column;
    }

    public function setColumn(CellContainer $column): self
    {
        $this->column = $column;

        return $this;
    }

    public function getRow(): CellContainer
    {
        return $this->row;
    }

    public function setRow(CellContainer $row): self
    {
        $this->row = $row;

        return $this;
    }

    public function getBox(): Box
    {
        return $this->box;
    }

    public function setBox(Box $box): self
    {
        $this->box = $box;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        if (null === $number) {
            $this->number = null;

            return $this;
        }

        /** @var Cell $cell */
        foreach ($this->row->getCells() as $cell) {
            if ($cell->getNumber() === $number) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid cell number (%d, %d)! Row %d already contains number %d',
                        $this->getRow()->getNumber(),
                        $this->getColumn()->getNumber(),
                        $this->getRow()->getNumber(),
                        $number
                    )
                );
            }
        }

        /** @var Cell $cell */
        foreach ($this->column->getCells() as $cell) {
            if ($cell->getNumber() === $number) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid cell number (%d, %d)! Column %d already contains number %d',
                        $this->getRow()->getNumber(),
                        $this->getColumn()->getNumber(),
                        $this->getColumn()->getNumber(),
                        $number
                    )
                );
            }
        }

        foreach ($this->box->getCells() as $cell) {
            if ($cell->getNumber() === $number) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Invalid cell number (%d, %d)! Box (%d, %d) already contains number %d',
                        $this->getRow()->getNumber(),
                        $this->getColumn()->getNumber(),
                        $this->getBox()->getHorizontalIndex(),
                        $this->getBox()->getVerticalIndex(),
                        $number
                    )
                );
            }
        }

        $this->number = $number;

        $this->possibilities->clear();

        /** @var Cell $cell */
        foreach ($this->row->getCells() as $cell) {
            $cell->removePossibility($number);
        }

        /** @var Cell $cell */
        foreach ($this->column->getCells() as $cell) {
            $cell->removePossibility($number);
        }

        /** @var Cell $cell */
        foreach ($this->box->getCells() as $cell) {
            $cell->removePossibility($number);
        }

        return $this;
    }

    public function getPossibilities(): Collection
    {
        return $this->possibilities;
    }

    public function hasPossibility(int $possibility): bool
    {
        return $this->possibilities->contains($possibility);
    }

    public function addPossibility(int $possibility): self
    {
        if (!$this->hasPossibility($possibility)) {
            $this->possibilities->add($possibility);
        }

        return $this;
    }

    public function removePossibility(int $possibility, bool $remove = true): self
    {
        if ($this->hasPossibility($possibility)) {
            $this->possibilities->removeElement($possibility);
        }

        if ($this->possibilities->count() === 1 && $remove) {
            $this->setNumber($this->possibilities->first());
        }

        return $this;
    }

    public function isOriginal(): bool
    {
        return $this->original;
    }

    public function setOriginal(bool $original): self
    {
        $this->original = $original;

        return $this;
    }
}