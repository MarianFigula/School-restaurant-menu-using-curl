<?php
// TODO: regex na odstranovanie specal chars -> '/^\W+|\W+$/'

require_once('config.php');
require_once('eat_meet.php');
require_once('drag.php');
require_once('free_food.php');

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/utility.css">
    <title>Zadanie2</title>
</head>
<body>


<div class="d-flex pt-2 justify-content-center"><h1>Zadanie 2</h1></div>

<header class="mb-3 mt-2">
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Domov</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="api-check.php">Overenie metód API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="api-describe.php">Popis metód API</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="justify-content-center div-center pb-4">
        <button class="btn btn-secondary m-1" type="button" name="tyzden" id="week">Celý týždeň</button>
        <button class="btn btn-secondary m-1" type="button" name="pondelok" onclick="getFoodByDayAllRest('pondelok')">Pondelok</button>
        <button class="btn btn-secondary m-1" type="button" name="utorok" onclick="getFoodByDayAllRest('utorok')">Utorok</button>
        <button class="btn btn-secondary m-1" type="button" name="streda" onclick="getFoodByDayAllRest('streda')">Streda</button>
        <button class="btn btn-secondary m-1" type="button" name="stvrtok" onclick="getFoodByDayAllRest('štvrtok')">Štvrtok</button>
        <button class="btn btn-secondary m-1" type="button" name="piatok" onclick="getFoodByDayAllRest('piatok')">Piatok</button>
        <button class="btn btn-secondary m-1" type="button" name="sobota" onclick="getFoodByDayAllRest('sobota')">Sobota</button>
        <button class="btn btn-secondary m-1" type="button" name="nedela" onclick="getFoodByDayAllRest('nedeľa')">Nedeľa</button>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="blue-bg-color p-2 border rounded">
                <h2 class="center-text text-light">Eat and meet</h2>
            </div>
            <div class="container-md pt-2 mb-4">

                <ul class="list-group list-group-flush" id="eat-ul">

                </ul>
            </div>
        </div>
        <div class="col-sm">
            <div class="orange-bg-color p-2 border rounded">
                <h2 class="center-text text-light">Drag</h2>
            </div>
            <div class="container-md pt-2 mb-4">
                <ul class="list-group list-group-flush" id="drag-ul">

                </ul>
            </div>
        </div>
        <div class="col-sm">
            <div class="grey-bg-color p-2 border rounded">
                <h2 class="center-text text-light">Free Food</h2>
            </div>
            <div class="container-md pt-2 mb-4">
                <ul class="list-group list-group-flush" id="free-food-ul">

                </ul>
            </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center py-3 my-4 mt-4 border-top border-dark-subtle">
    <span class="text-muted">© 2023 Marián Figula</span>
</footer>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
<script>


    let weekBtn = document.getElementById('week').addEventListener('click', function () {
        getAllFoodByOneRest('Eat and meet','eat-ul');
        getAllFoodByOneRest('Drag','drag-ul');
        getAllFoodByOneRest('Free food','free-food-ul');
    })


    window.onload = async function () {
        await getAllFoodByOneRest('Eat and meet','eat-ul');
        await getAllFoodByOneRest('Drag','drag-ul');
        await getAllFoodByOneRest('Free food','free-food-ul');
    }


    async function getAllFoodByOneRest(nazov_jedalne, id){

        const res = await axios.get('https://site87.webte.fei.stuba.sk/zadanie2/api.php');
        const items = res.data;
        const ul = document.getElementById(id);
        let string = '';
        ul.innerHTML = "";
        string += await getFoodByDay(nazov_jedalne, id, 'pondelok')
        string += await getFoodByDay(nazov_jedalne, id, 'utorok')
        string += await getFoodByDay(nazov_jedalne, id, 'streda')
        string += await getFoodByDay(nazov_jedalne, id, 'štvrtok')
        string += await getFoodByDay(nazov_jedalne, id, 'piatok')
        string += await getFoodByDay(nazov_jedalne, id, 'sobota')
        string += await getFoodByDay(nazov_jedalne, id, 'nedeľa')

        ul.innerHTML = string;
    }

    async function getFoodByDayAllRest(day){
        await getFoodByDay('Eat and meet', 'eat-ul', day);
        await getFoodByDay('Drag','drag-ul', day);
        await getFoodByDay('Free food', 'free-food-ul', day);
    }

    async function getFoodByDay(nazov_jedalne,id,day){
        const res = await axios.get(`https://site87.webte.fei.stuba.sk/zadanie2/api.php?den=${day}`);
        const items = res.data;
        const ul = document.getElementById(id);
        let string = '';
        let color;
        switch (nazov_jedalne){
            case "Eat and meet": color = "#427aa1";break;
            case "Drag": color = "#bc3908"; break;
            case "Free food": color = "#4c5c68";break;
            default:  color = "";
        }

        let tmpDay = '';
        for (const item in items) {
            console.log(items[item].nazov_jedalne);
            if (items[item].nazov_jedalne === nazov_jedalne){
                if (tmpDay != items[item].den){
                    string += `<h3 class='pt-4'>${items[item].den.charAt(0).toUpperCase() + items[item].den.slice(1)}<hr></h3>`;
                    tmpDay = items[item].den;
                    console.log(tmpDay);
                }
                if (items[item].img !== null){
                    string += "<li class='list-group-item'><span class='fw-bold blue-color'>" + items[item].typ_jedla + "</span> " +
                        items[item].nazov + " " + items[item].cena + `<br><img src=${items[item].img} alt='obrazok' width='100px' class='mt-3'/></li>`;
                }else if (items[item].img == null) {
                    string += `<li class='list-group-item'><span class='fw-bold' style=color:${color}>`+ items[item].typ_jedla + "</span> " +
                        items[item].nazov + " " + items[item].cena + " " + "</li>";
                }
            }
        }
        ul.innerHTML = string;
        return string;
    }
</script>
</body>
</html>
