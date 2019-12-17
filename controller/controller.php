<?php

require_once 'model/DataAccess.php';

class Controller {

    function connectToDatabase() {
        $db = new DataAccess();
        if (!$db) {
            echo $db->lastErrorMsg();
        } else {
            echo "Opened database successfully\n";
        }
    }

}
