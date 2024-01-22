<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Component;

class Box extends CellContainer
{
    public function __construct(
        private int $verticalIndex,
        private int $horizontalIndex,
    )
    {
        parent::__construct();
    }

    public function getVerticalIndex(): int
    {
        return $this->verticalIndex;
    }

    public function setVerticalIndex(int $verticalIndex): self
    {
        $this->verticalIndex = $verticalIndex;

        return $this;
    }

    public function getHorizontalIndex(): int
    {
        return $this->horizontalIndex;
    }

    public function setHorizontalIndex(int $horizontalIndex): self
    {
        $this->horizontalIndex = $horizontalIndex;

        return $this;
    }
}