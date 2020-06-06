<?php
echo '
<h1>Personen anmelden</h1>';
/*  Erstellen Sie ein Formular:
    Vorname Nachname als Eingabefeld
    Funktion (eine Auswahl) und Lehrberuf (mehrfach auswahl) aus Liste auswählen */
if(isset($_POST['send']))
{
    try
    {
        /* Aufgabe: Geben Sie die vom Benutzer
           eingegebenen Formulardaten aus */
        $vname = $_POST['vname'];
        $nname = $_POST['nname'];
        $funktion = $_POST['Funktion'];
        $lehrberuf = $_POST['Lehrberuf'];
        echo '<h3>Sie haben folgendes angegeben:</h3>';
        echo '<form method="post">';
        echo '<label>Vorname: </label><br>'.$vname.'<br><br>
              <label>Nachname: </label><br>'.$nname.'<br><br>';

        $queryFunktionsname = 'select fun_name
                  from funktion where fun_id = ?';
        $selFunktionsname = $con->prepare($queryFunktionsname);
        $selFunktionsname->execute([$funktion]);
        $row = $selFunktionsname->fetch(PDO::FETCH_NUM);
        echo'<label>Funktion: </label><br>'.$row[0].'<br><br>';

        echo'<label>Lehrberuf: </label>';
        $queryLBName = 'select leh_name_kurz from Lehrberuf where leh_id = ?';
        $selLBName = $con->prepare($queryLBName);
        for ($i = 0; $i < sizeof($lehrberuf); $i++){
            $selLBName->execute([$lehrberuf[$i]]);
            $rowLB = $selLBName->fetch(PDO::FETCH_NUM);
            echo '<br>'.$rowLB[0];
        }
        echo '<br><br>
        
        </form>';

        echo
            '<form method="post">
            <p>Daten speichern</p>
            <input type="hidden" name="vname" value="'.$vname.'" </input>
            <input type="hidden" name="nname" value="'.$nname.'" </input>
            <input type="hidden" name="funktion" value="'.$funktion.'" </input>';
        for ($i = 0; $i < sizeof($lehrberuf); $i++)
        {
            echo '<input type="hidden" name="lehrberuf[]" value="'.$lehrberuf[$i].'" </input>';
        }

        echo '<input type="submit" name="saveit" value="Speichern">
            <input type="button" onclick="history.back()" value="Abbrechen"
        </form>';

    } catch(Exception $e)
    {
        echo $e->getMessage();
    }
}

else if(isset($_POST['saveit'])){
    $vname = $_POST['vname'];
    $nname = $_POST['nname'];
    $funktion = $_POST['funktion'];
    $lehrberuf = $_POST['lehrberuf'];

    echo '<br>';
    echo '<label>Vorname:</label>&nbsp&nbsp'.$vname;
    echo '<br>';
    echo '<br>';
    echo '<label>Nachname: </label>&nbsp&nbsp'.$nname;
    echo '<br>';
    echo '<br>';

    $queryFunktionsname = 'select fun_name
                  from funktion where fun_id = ?';
    $selFunktionsname = $con->prepare($queryFunktionsname);
    $selFunktionsname->execute([$funktion]);
    $row = $selFunktionsname->fetch(PDO::FETCH_NUM);
    echo '<label>Funktion: </label>&nbsp&nbsp'.$row[0];

    echo '<br>';

    $queryLBName = 'select leh_name_kurz from Lehrberuf where leh_id = ?';
    $selLBName = $con->prepare($queryLBName);
    for ($i = 0; $i < sizeof($lehrberuf); $i++){
        $selLBName->execute([$lehrberuf[$i]]);
        $rowLB = $selLBName->fetch(PDO::FETCH_NUM);
        echo '<br><label>Lehrberuf:</label>&nbsp&nbsp'.$rowLB[0];
    }

    try{
        $insertP = 'insert into person (person_vname, person_nname) values(?, ?)';
        $insertPerson = $con->prepare($insertP);
        $insertPerson->execute([$vname, $nname]);
        $perid = $con->lastInsertId();

        for ($i = 0; $i < sizeof($lehrberuf); $i++) {
            $insertPeLe = 'insert into person_lehrberuf (person_id, leh_id, fun_id) values(?, ?, ?)';

            $insertPeLe = $con->prepare($insertPeLe);
            $insertPeLe->execute([$perid, $lehrberuf[$i], $funktion]);
        }
    } catch(exception $e){
        echo $e->getMessage();
    }
}
else
{
    ?>

    <form method="post">
        <div class="form-group">
            <label for="vn">Vorname: </label>
            <input class="form-control" id="vn" type="text" name="vname"
                   placeholder="Vorname"
                   required><br>
        </div>
        <div class="form-group">
            <label for="nn">Nachname: </label>
            <input class="form-control" id="nn" type="text" name="nname"
                   placeholder="Nachname"
                   required><br>
        </div>
        <?php

        $queryFunktion = 'select fun_id, fun_name
                  from funktion 
                  order by fun_name';
        $selFunktion = $con->prepare($queryFunktion);
        $selFunktion->execute();
        echo '<label>Funktion:</label>
          <div class="form-group">
          <select name="Funktion">';
        while ($row = $selFunktion->fetch(PDO::FETCH_NUM)) {
            echo '<option value="'.$row[0].'">'.$row[1];
        }
        echo '</select></div><br>';

        $queryLehrberuf = 'select leh_id, leh_name_kurz from Lehrberuf order by leh_name_kurz';
        $selLehrberuf = $con->prepare($queryLehrberuf);
        $selLehrberuf->execute();

        echo '<label>Lehrberuf:</label>
          <div class="form-group">
          <select multiple name="Lehrberuf[]">';
        while ($row = $selLehrberuf->fetch(PDO::FETCH_NUM)) {
            echo '<option class="form group" value="'.$row[0].'">'.$row[1];
        }
        echo '</select></div><br>
        
    <input type="submit" name="send" value="speichern">
    </form>';

        ?>
    </form>
    <?php
}
