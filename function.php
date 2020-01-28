<?php

    function afficher($pdo,$sql){
        $sql = $pdo->prepare($sql);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function creteTable($sql,$arr){
        echo '<table class="table table-bordered table-dark"><tr>';
        foreach ($arr as $ar){
            echo "<th>".$ar."</th>";
        }
        echo "</tr>";
        foreach ($sql as $row){
            echo "<tr>";
            foreach ($row as $r){
                echo "<td>".$r."</td>";
            }
            echo "</tr>";
        }
        echo '</table>';
    }
