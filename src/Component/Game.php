<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Component;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Game
{
    public function __construct(
        /** @var Collection|CellContainer[] $rows */
        private Collection $rows = new ArrayCollection(),
        /** @var Collection|CellContainer[] $columns */
        private Collection $columns = new ArrayCollection(),
        /** @var Collection|Box[] $boxes */
        private Collection $boxes = new ArrayCollection(),
    ) {}

    public function getRows(): Collection
    {
        return $this->rows;
    }

    public function addRow(CellContainer $row): self
    {
        if (!$this->rows->contains($row)) {
            $this->rows->add($row);
        }

        return $this;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function addColumn(CellContainer $column): self
    {
        if (!$this->columns->contains($column)) {
            $this->columns->add($column);
        }

        return $this;
    }

    public function getBoxes(): Collection
    {
        return $this->boxes;
    }

    public function addBox(Box $box): self
    {
        if (!$this->boxes->contains($box)) {
            $this->boxes->add($box);
        }

        return $this;
    }

    public function getBoxByIndex(int $horizontalIndex, int $verticalIndex): Box
    {
        foreach ($this->boxes as $box) {
            if ($box->getHorizontalIndex() === $horizontalIndex && $box->getVerticalIndex() === $verticalIndex) {
                return $box;
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Invalid indices provided! (%d,%d)', $horizontalIndex, $verticalIndex)
        );
    }

    public function getBoxByCellIndex(int $horizontalIndex, int $verticalIndex): Box
    {
        return $this->getBoxByIndex(
            (int) floor($horizontalIndex / 3),
            (int) floor($verticalIndex / 3)
        );
    }

    public function getColumnByIndex(int $columnIndex): CellContainer
    {
        foreach ($this->columns as $column) {
            if ($column->getNumber() === $columnIndex) {
                return $column;
            }
        }

        throw new \InvalidArgumentException(sprintf('Invalid column index \'%d\'', $columnIndex));
    }

    public function getRowByIndex(int $rowIndex): CellContainer
    {
        foreach ($this->rows as $row) {
            if ($row->getNumber() === $rowIndex) {
                return $row;
            }
        }

        throw new \InvalidArgumentException(sprintf('Invalid row index \'%d\'', $rowIndex));
    }

    public function getCellByIndices(int $rowIndex, int $columnIndex): Cell
    {
        foreach ($this->rows as $row) {
            if ($row->getNumber() === $rowIndex) {
                /** @var Cell $cell */
                foreach ($row->getCells() as $cell) {
                    if ($cell->getColumn()->getNumber() === $columnIndex) {
                        return $cell;
                    }
                }
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Invalid cell indices (%d,%d)', $rowIndex, $columnIndex)
        );
    }
}