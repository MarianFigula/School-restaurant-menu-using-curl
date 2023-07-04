<?php

require_once ('config.php');
require_once ('eat_meet.php');
require_once ('drag.php');
require_once ('free_food.php');

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
    <title>Overenie metód API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="css/utility.css">
</head>
<body>


<?php
    if (isset($_POST["download"])){
        dropAllFromTable($db, 'html');
        dropAllFromTable($db, 'jedalne');
        $success = fetchHTMLEatMeet($db) && fetchHTMLDrag($db) && fetchHTMLFreeFood($db);
        if ($success){
            echo "<script type='text/javascript'>toastr.success('Stránky úspešne stiahnuté. ' +
            'Ak stránky už boli niekedy stiahnuté, vymazali sa a nanovo stiahli')</script>";
        }
    }elseif (isset($_POST["parse"])){
        dropAllFromTable($db, 'jedalne');
        $success = insertToDatabaseEatMeet($db) && insertToDatabaseDrag($db) && insertToDatabaseFreeFood($db);
        unset($_POST);
        header("Location: ".$_SERVER['PHP_SELF']);
    } elseif (isset($_POST["delete"])){
        $count = 0;
        dropAllFromTable($db, "html") ? $count++ : $count--;
        dropAllFromTable($db, "jedalne") ? $count++: $count--;
        if ($count == 2){
            echo "<script type='text/javascript'>toastr.success('Stránky úspešne vymazané')</script>";
        }
    }

    function read_food($db){
        $stmt = $db->query("SELECT * from jedalne;");
        $menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo json_encode($games);
        foreach ($menu as $row){
            echo "<option name='food-id' value='{$row['id']}'>" . $row['nazov_jedalne'] . ", " . $row['den']. ", ". $row['typ_jedla'] . ", " .
                $row['nazov'] . " " . $row['cena'] . "</option>";
        }
    }

    function get_food_by_day($db, $day){
        $stmt = $db->query("SELECT * from jedalne WHERE den=$day;");
        $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function dropAllFromTable($db, $db_name){
        $del_html = "DELETE FROM $db_name";
        $stmt = $db->prepare($del_html);
        return $stmt->execute();
    }
?>

<div class="d-flex pt-2 justify-content-center"><h1>Overenie vytvorených metód API</h1></div>

<header class="mb-3 mt-2" >
    <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Domov</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="api-check.php">Overenie metód API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="api-describe.php">Popis metód API</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="row">
            <div class="col">
                <div class="d-flex">
                    <h5 class="mt-2">Stiahnúť stránky: </h5>
                    <div class="form-group m-1"><button class="btn btn-primary ms-3" name="download" type="submit">Stiahnúť</button></div>
                </div>
            </div>
            <div class="col">
                <div class="d-flex">
                    <h5 class="mt-2">Rozparsovať stránky: </h5>
                    <div class="form-group m-1"><button class="btn btn-primary ms-3" name="parse" type="submit">Rozparsovať</button></div>
                </div>
            </div>
            <div class="col">
                <div class="d-flex">
                    <h5 class="mt-2">Vymazať stránky: </h5>
                    <div class="form-group m-1"><button class="btn btn-danger ms-3" name="delete" type="submit">Vymazať</button></div>
                </div>
            </div>
        </div>
    </form>
</div>
<hr>
<div class="container border rounded bg-secondary">
    <div class="width-50 p-4">
        <div class="border p-4 bg-light">
            <h2 class="justify-content-center center-text">Zmeniť cenu jedla</h2>
            <hr class="mb-3 mt-3">
            <!--<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">-->
                <div class="mb-3 row">
                    <div class="col form-group">
                        <label for="all-food" class="form-label">Zoznam jedál</label>
                        <select name="all-food" id="all-food" class="form-control">
                            <?php
                            read_food($db);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col form-group">
                        <label for="nova-cena" class="form-label">Nová cena</label>
                        <input type="number" name="nova-cena" id="nova-cena" class="form-control">
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col form-group"><button type="button" class="btn btn-primary" name="btn-get-food" id="btn-get-food" onclick="updatePrice()">Upraviť</button></div>
                </div>
            <!--</form>-->
        </div>
    </div>


    <div class="width-50 p-4">
        <div class="border p-4 bg-light">
            <h2 class="justify-content-center center-text">Vymazať jedlo</h2>
            <hr class="mb-3 mt-3">
            <div class="mb-3 row">
                    <div class="col form-group">
                        <label for="all-food-delete" class="form-label">Zoznam jedál</label>
                        <select name="all-food-delete" id="all-food-delete" class="form-control">
                            <?php
                            read_food($db);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col form-group"><button type="button" class="btn btn-danger" name="btn-del-food" id="btn-del-food" onclick="deleteFood()">Vymazať</button></div>
                </div>
        </div>
    </div>

    <div class="width-50 p-4">
        <div class="border p-4 bg-light">
            <h2 class="justify-content-center center-text">Pridať jedlo na celý týždeň</h2>
            <hr class="mb-3 mt-3">
                <div class="mb-3 row">
                    <div class="col form-group">
                        <label for="all-food-add" class="form-label">Zoznam reštaurácií</label>
                        <select name="nazov_jedalne" id="all-food-add" class="form-control">
                            <option value="Eat and meet">Eat and meet</option>
                            <option value="Drag">Drag</option>
                            <option value="Free food">Free food</option>
                        </select>
                    </div>
                    <div class="col form-group">
                        <label for="food-type-add" class="form-label">Typ jedla</label>
                        <select name="typ_jedla" id="food-type-add" class="form-control">
                            <option value="Polievka" name="soup">Polievka</option>
                            <option value="Menu" name="menu">Menu</option>
                            <option value="Mimo menu" name="other">Mimo menu</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col form-group">
                        <label for="name-add" class="form-label">Názov jedla</label>
                        <input type="text" name="nazov" id="name-add" class="form-control" required>
                    </div>
                    <div class="col form-group">
                        <label for="cena-add" class="form-label">Cena</label>
                        <input type="number" name="cena" id="cena-add" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col form-group"><button type="button" class="btn btn-primary" name="btn-add-food" id="btn-add-food">Pridať</button></div>
                </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center py-3 my-4 mt-4 border-top border-dark-subtle">
    <span class="text-muted">© 2023 Marián Figula</span>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
<script>
    async function updatePrice(){
        let e = document.getElementById("all-food");
        let id = e.options[e.selectedIndex].value;
        let novaCena = document.getElementById("nova-cena")


        console.log("CENA :" + novaCena.value)

        if (parseInt(novaCena.value) < 0 || novaCena.value == ""){
            toastr.error('Forumlár je zlé vyplnený!, Skúste to znovu.');
            return;
        }


        const data = {
            id: id,
            price: novaCena.value.toString() + "€"
        };
        const res = await axios.put('https://site87.webte.fei.stuba.sk/zadanie2/api.php',data,{headers: {"Content-Type": "application/json"}})
            .then(r => {
                //console.log(r.data)
                console.log(r.status)
                if(r.status == 200 || r.status == 204){
                    toastr.success('Zmena prebehla úspešne, aktualizujem zmeny!')
                    setTimeout(() => {
                        document.location.reload();
                    }, 500);

                }
            })
            .catch(e => {
                console.log(e)
                toastr.error('Nastala chyba!, Skúste to znovu.');
            })
    }

    async function deleteFood(){
        let e = document.getElementById("all-food-delete");
        let id = e.options[e.selectedIndex].value;
        const res = await axios.delete(`https://site87.webte.fei.stuba.sk/zadanie2/api.php?id=${id}`)
            .then(r => {
                console.log(r.data)
                if(r.status == 200){
                    toastr.success('Odstránenie prebehlo úspešne, aktualizujem zmeny!')
                    setTimeout(() => {
                        document.location.reload();
                    }, 1500);
                }

            })
            .catch(e => console.log(e));
    }

    document.getElementById('btn-add-food').addEventListener('click', async function addFood(){
        let e = document.getElementById("all-food-add");
        let restauracia = e.options[e.selectedIndex].value;

        let t = document.getElementById("food-type-add");
        let typ = t.options[t.selectedIndex].value;
        let nazov = document.getElementById("name-add").value
        let cena = document.getElementById("cena-add").value



        if (parseInt(cena) < 0 || cena == "" || nazov == ""){
            toastr.error('Forumlár je zlé vyplnený!, Skúste to znovu');
            return;
        }

        const data = {
            nazov_jedalne: restauracia,
            typ_jedla: typ,
            nazov: nazov,
            cena: cena + "€"
        }


        const res = await axios.post('https://site87.webte.fei.stuba.sk/zadanie2/api.php',data,{headers: {"Content-Type": "application/json"}})
            .then(r => {
                console.log(r)
                console.log(r.data)
                if(r.status == 201){
                    toastr.success('Zmena prebehla úspešne, aktualizujem zmeny!');
                    setTimeout(() => {
                        document.location.reload();
                    }, 500);
                }
            })
            .catch(e => {
                console.log(e)
                toastr.error('Nastala chyba!, Skúste to znovu');
            })
    })

</script>
</body>
</html>
