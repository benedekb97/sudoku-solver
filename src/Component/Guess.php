<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Component;

readonly class Guess
{
    public function __construct(
        private Cell $cell,
        private int  $number
    ) {}

    public function getCell(): Cell
    {
        return $this->cell;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}