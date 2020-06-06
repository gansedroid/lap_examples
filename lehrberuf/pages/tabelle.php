<?php
function getTable($query, $tableName)
{
    include 'config.php';
    try {
        $select = $con->prepare($query);
        $select->execute();
        echo '<table class="table table-striped table-hover table-bordered">
           <caption>'.$tableName.'</caption>
           <tr>';
        $countAttr = $select->columnCount();
        for ($i = 0; $i < $countAttr; $i++) {
            $meta[] = $select->getColumnMeta($i);
            echo '<th>' . $meta[$i]['name'] . '</th>';
        }

        echo '</tr>';
        while ($row = $select->fetch(PDO::FETCH_NUM)) {
            echo '<tr>';
            foreach ($row as $r) {
                echo '<td>' . $r . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';


    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

echo '
<h1> </h1>';
try{
    $query = 'select * from lehrberuf order by leh_name_kurz';
    getTable($query, 'Lehrberuf');

    $query = 'select p.*, fun_name from person p natural join funktion natural join person_lehrberuf natural join lehrberuf where leh_name_kurz = "ITI"';
    getTable($query, 'Alle Personen mit Lehrberuf ITI');

}catch(Exception $e){
    echo $e->getMessage();
}



?>