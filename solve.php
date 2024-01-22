<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Benedekb97\Sudoku\Checker\GameSolveStateChecker;
use Benedekb97\Sudoku\Component\Cell;
use Benedekb97\Sudoku\Component\CellContainer;
use Benedekb97\Sudoku\Factory\GameFactory;
use Benedekb97\Sudoku\Factory\GameSolverFactory;
use Benedekb97\Sudoku\Populator\GamePopulator;
use Symfony\Component\HttpFoundation\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();

$gameFactory = new GameFactory();

$game = $gameFactory->createGame();

$request = Request::createFromGlobals();

$populator = new GamePopulator();

$populator->populate($game, $request);

$gameSolverFactory = new GameSolverFactory();
$solver = $gameSolverFactory->create();

$game = $solver->solve($game);

$gameSolveStateChecker = new GameSolveStateChecker();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Solved!</title>
        <link rel="stylesheet" href="css/main.css" />
    </head>
    <body>
        <table>
            <?php
            /** @var CellContainer $row */
            foreach ($game->getRows() as $row) {
                ?>
                <tr>
                    <?php
                    /** @var Cell $cell */
                    foreach ($row->getCells() as $cell) {
                        ?>
                        <td class="number <?= $cell->isOriginal() ? 'original' : '' ?>">
                            <?php
                            if (null !== $cell->getNumber()) {
                                echo $cell->getNumber();
                            } else {
                                ?>
                                <table class="possibilities">
                                <?php
                                for ($number = 1; $number <= 9; $number++) {
                                    if ($number % 3 === 1) {
                                        echo "<tr>";
                                    }
                                    echo "<td class='possibility'>";
                                    if ($cell->hasPossibility($number)) {
                                        echo $number;
                                    }
                                    echo "</td>";
                                    if ($number % 3 === 0) {
                                        echo "</tr>";
                                    }
                                }
                                ?>
                                </table>
                                <?php
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>
