<?php

require_once ('config.php');

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
    <title>Popis metód API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/utility.css">
</head>

<body>

<div class="d-flex pt-2 justify-content-center"><h1>Popis vytvorených metód API</h1></div>

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
                        <a class="nav-link" aria-current="page" href="api-check.php">Overenie metód API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="api-check.php">Popis metód API</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container border rounded">
    <div class=" div-center center-text mt-2 pt-1">
        <h2>API pre manažment jedál</h2>
    </div>
    <hr>
    <section>
        <h4>Získať menu pre celý týždeň</h4>
        <hr>
        <div>
            <p class="fw-semibold fs-5">URL</p>
            <p>
            <code class="p-1 fs-6">https://site87.webte.fei.stuba.sk/zadanie2/api.php</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">HTTP Metóda</p>
            <p>
                <code class="p-2 ps-3 pe-3 fs-5 blue-method-bg-color white-text-color rounded">GET</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">Parametre</p>
            <p>
                Bez parametrov
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">Odpoveď</p>
            <p>
                <code class="p-1 me-2 fs-6">200</code>
                <span>OK</span>
            </p>
        </div>
    </section>
    <section>
        <h4>Získať menu pre daný deň</h4>
        <hr>
        <div>
            <p class="fw-semibold fs-5">URL</p>
            <p>
                <code class="p-1 fs-6">https://site87.webte.fei.stuba.sk/zadanie2/api.php?den={den}</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">HTTP Metóda</p>
            <p>
                <code class="p-2 ps-3 pe-3 fs-5 blue-method-bg-color white-text-color rounded">GET</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">Parametre</p>
            <p>
                <span class="me-2">Path:</span> den <span class="ms-2 fst-italic">String</span>
            </p>
        </div>
        <div >
            <p class="fw-semibold fs-5">Odpoveď</p>
            <p>
                <code class="p-1 me-2 fs-6">200</code>
                <span>OK</span>
            </p>
            <p>
                <code class="p-1 me-2 fs-6">400</code><span>BAD REQUEST</span>
            </p>
        </div>
    </section>
    <section>
        <h4>Pridať jedlo pre celý týždeň pre danú reštauráciu</h4>
        <hr>
        <div>
            <p class="fw-semibold fs-5">URL</p>
            <p>
                <code class="p-1 fs-6">https://site87.webte.fei.stuba.sk/zadanie2/api.php</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">HTTP Metóda</p>
            <p>
                <code class="p-2 ps-3 pe-3 fs-5 green-method-bg-color white-text-color rounded">POST</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">Parametre</p>
            <span class="me-2">Body:</span>
            <ul>
                <li>nazov_jedalne <span class="ms-2 fst-italic">String</span></li>
                <li>typ_jedla <span class="ms-2 fst-italic">String</span></li>
                <li>nazov <span class="ms-2 fst-italic">String</span></li>
                <li>cena <span class="ms-2 fst-italic">String</span></li>
            </ul>
        </div>
        <div>
            <p class="fw-semibold">Odpoveď</p>
            <p>
                <code class="p-1 me-2 fs-6">201</code><span>CREATED</span>
            </p>
            <p>
                <code class="p-1 me-2 fs-6">400</code><span>BAD REQUEST</span>
            </p>
        </div>
    </section>
    <section>
        <h4>Upraviť cenu jedla</h4>
        <hr>
        <div>
            <p class="fw-semibold fs-5">URL</p>
            <p>
                <code class="p-1 fs-6">https://site87.webte.fei.stuba.sk/zadanie2/api.php</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">HTTP Metóda</p>
            <p>
                <code class="p-2 ps-3 pe-3 fs-5 orange-method-bg-color white-text-color rounded">PUT</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">Parametre</p>

            <span class="me-2">Body:</span>
            <ul>
                <li>id <span class="ms-2 fst-italic">Integer</span></li>
                <li>cena <span class="ms-2 fst-italic">String</span></li>
            </ul>
        </div>
        <div>
            <p class="fw-semibold fs-5">Odpoveď</p>
            <p>
                <code class="p-1 me-2 fs-6">204</code><span>OK</span>
            </p>
            <p>
                <code class="p-1 me-2 fs-6">400</code><span>BAD REQUEST</span>
            </p>
        </div>
    </section>
    <section>
        <h4>Vymaž jedlo</h4>
        <hr>
        <div>
            <p class="fw-semibold fs-5">URL</p>
            <p>
                <code class="p-1 fs-6">https://site87.webte.fei.stuba.sk/zadanie2/api.php?id=${id}</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">HTTP Metóda</p>
            <p>
                <code class="p-2 ps-3 pe-3 fs-5 red-method-bg-color white-text-color rounded">DELETE</code>
            </p>
        </div>
        <div>
            <p class="fw-semibold fs-5">Parametre</p>

            <span class="me-2">Path:</span>
            <ul>
                <li>id <span class="ms-2 fst-italic">Integer</span></li>
            </ul>
        </div>
        <div>
            <p class="fw-semibold fs-5">Odpoveď</p>
            <p>
                <code class="p-1 me-2 fs-6">200</code><span>OK</span>
            </p>
            <p>
                <code class="p-1 me-2 fs-6">400</code><span>BAD REQUEST</span>
            </p>
        </div>
    </section>

</div>
<footer class="d-flex justify-content-center py-3 my-4 mt-4 border-top border-dark-subtle">
    <span class="text-muted">© 2023 Marián Figula</span>
</footer>
</body>
</html>
