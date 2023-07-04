<?php
header('Content-Type: application/json');

require_once ('config.php');
try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e){
    echo $e->getMessage();
}

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (empty($_GET['den'])){
            read_food($db);
            return;
        }else{
            get_food_by_day($db,$_GET['den']);
        }
        break;
    case 'POST':
        create_food($db,file_get_contents('php://input'));
        break;
    case 'PUT':
        //$_GET['id']
        parse_str(file_get_contents('php://input'), $_PUT);
        update_food_price($db, $_PUT);
        break;
    case 'DELETE':
        delete_food($db, $_GET['id']);
        break;
}

function read_food($db){
    //header('Content-Type: application/json');
    //SELECT nazov_jedalne,den,typ_jedla,nazov,cena from jedalne;
    $stmt = $db->query("SELECT DISTINCT  * from jedalne;");
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($menu);
}

function get_food_by_day($db, $day){
    if(!isEmpty($day)) {
        echo json_encode(array('error' => 'Get failed'));
        http_response_code(400);
        return;
    }
    //header('Content-Type: application/json');
    //SELECT nazov_jedalne,den,typ_jedla,nazov,cena from jedalne;
    $stmt = $db->query("SELECT DISTINCT * from jedalne WHERE den LIKE '%$day%';");
    $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($menu);

}

function update_food_price($db, $data){

    $json_str = array_key_first($data);
    // Parse the JSON string into a PHP object
    $obj = json_decode($json_str);

    // Access the values of the object using the arrow notation
    $id = $obj->id; // '212'
    $cena = $obj->price; // '120€'

    // Print the values
    //echo "IDECKO:" . $id . "\n";
    //echo "CENIKA:" . $cena . "\n";

    if(!isEmpty($id) || $cena < 0) {
        echo json_encode(array('error' => 'Update failed'));
        http_response_code(400);
        return;
    }
    $stmt = $db->prepare('UPDATE jedalne SET cena = :cena WHERE id = :id;');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':cena', $cena);
    $stmt->execute();
    http_response_code(204);
    echo json_encode(array('success' => 'Data updated successfully'));
}


function delete_food($db, $id) {
    if(!isEmpty($id)) {
        echo json_encode(array('error' => 'Delete failed'));
        http_response_code(400);
        return;
    }
    $stmt = $db->prepare('DELETE FROM jedalne WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(array('success' => 'Data deleted successfully'));
    http_response_code(200);
}

function create_food($db, $data){
    $decoded = json_decode($data);
    $days = 0;
    switch ($decoded->nazov_jedalne){
        case 'Eat and meet': $days=7; break;
        case 'Drag':
        case 'Free food': $days = 5; break;
    }

    for ($i = 1; $i <= $days; $i++){

        switch ($i) {
            case 1: $day = "Pondelok";break;
            case 2: $day = "Utorok";break;
            case 3: $day = "Streda";break;
            case 4: $day = "Štvrtok";break;
            case 5: $day = "Piatok";break;
            case 6: $day = "Sobota";break;
            case 7: $day = "Nedeľa";break;
            default: $day = "";
        }

        $nazov_jedalne = $decoded->nazov_jedalne;
        $typ_jedla = strtoupper($decoded->typ_jedla);
        $nazov = $decoded->nazov;
        $cena = $decoded->cena;

        if ($cena <= 0){
            echo json_encode(array('error' => 'Delete failed'));
            http_response_code(400);
            return;
        }

        $stmt = $db->prepare("INSERT INTO jedalne (nazov_jedalne,den,typ_jedla,nazov,cena) VALUES (:nazov_jedalne,:den ,:typ_jedla,:nazov,:cena);");
        $stmt->bindParam(':nazov_jedalne', $nazov_jedalne);
        $stmt->bindParam(':den', $day);
        $stmt->bindParam(':typ_jedla', $typ_jedla);
        $stmt->bindParam(':nazov', $nazov);
        $stmt->bindParam(':cena', $cena);
        $stmt->execute();

        echo json_encode(['success' => true]);
        http_response_code(201);
        //header("location: api-check.php");

    }
}

function isEmpty($param): bool
{
    if(empty($param)) {
        $isOk = false;
    } else {
        $isOk = true;
    }
    return $isOk;
}
