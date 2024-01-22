<?php

declare(strict_types=1);

namespace Benedekb97\Sudoku\Populator;

use Benedekb97\Sudoku\Component\Game;
use Symfony\Component\HttpFoundation\Request;

class GamePopulator
{
    public function populate(Game $game, Request $request): void
    {
        $fields = $request->get('fields');

        for ($rowIndex = 0; $rowIndex < 9; $rowIndex++) {
            for ($columnIndex = 0; $columnIndex < 9; $columnIndex++) {
                if (!empty($value = $fields[$rowIndex][$columnIndex])) {
                    $game->getCellByIndices($rowIndex, $columnIndex)->setNumber((int)$value)->setOriginal(true);
                }
            }
        }
    }
}