<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sudoku</title>
    </head>
    <body>
    <form action="solve.php" method="POST">
        <table>
        <?php
        for ($row = 0; $row < 9; $row++) {
            ?>
                <tr>
                <?php
                for ($column = 0; $column < 9; $column++) {
                    ?>
                    <td>
                        <input type="number" min="1" max="9" name="fields[<?= $row ?>][<?= $column ?>]" />
                    </td>
                    <?php
                }
                ?>
                </tr>
            <?php
        }
        ?>
        </table>
        <input type="submit" value="Solve">
    </form>
    </body>
</html>