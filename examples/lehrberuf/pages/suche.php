<?php
echo '<h2>Suche nach Personen</h2><br><h3>Suche nach:</h3>';

echo '
<br>
<ol>
  <li>Vorname</li>
  <li>Nachname</li>
  <li>Nachname in bestimmten Lehrberuf</li>
  <li>usw</li>
</ol>';

echo '<br><form method="post">
        <div class="form-group">
            <label for="vn">Suche nach Vor- oder Nachname: </label>
            <input class="form-control" id="vn" type="text" name="vname"
                   placeholder="Vorname"><br>
        </div>
        
        
        <input id="vorname" type="radio" value="vorname" name="name">
        <label for="vorname">in Vornamen suchen</label>
        <br>
        <input id="nachname" type="radio" value="nachname" name="name">
        <label for="nachname">in Nachnamen suchen</label>
        <br>
        <br>
       <input type="submit" name="send" value="Suche">';

if(isset($_POST['send']))
{
    try
    {
        $name = $_POST['vname'];

        $query = 'select p.* from person p where person_vname like ?';
        $select = $con->prepare($query);
        $name = '%'.$name.'%';
        $select->execute([$name]);
        echo '<table class="table table-striped table-hover table-bordered">
           <caption>Personen</caption>
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

    } catch(Exception $e)
    {
        echo $e->getMessage();
    }
}