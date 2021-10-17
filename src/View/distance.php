<?php

$listDivsOpen = '<div class="col alert alert-info  border-info border-end border-bottom pt-1 pb-1 text-center">';
$listDivsClose = '</div>';
$listHeaderDivsOpen = '<div class="col alert alert-success border-dark bg-info border-end pt-1 pb-1 text-center">';

include_once __DIR__ . '/Layout/header.php';

echo '      
    <header>      
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Distance calculation</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>          
                <div class="collapse navbar-collapse" id="navbarCollapse">
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-shrink-0 m-5">
        <div class="container">
            <h1 class="mt-5">MapPost practical test with distance calculation</h1>
            <p class="lead">Up to <b>ten</b> locations.</p>
            <div id="message"></div>
            <form id="addresses_form">
                <div id="inputs">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="dravnieku 10 riga" aria-label="Address" name="addresses[]" minlength="10"  maxlength="200" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="dravnieku 10 riga" aria-label="Address2" name="addresses[]" minlength="10"  maxlength="200" required>
                    </div>
                </div>
                    <button id="add_button" class="btn btn-info" type="button"  onclick="add()">Add address</button>
                    <button id="calculate_button" class="btn btn-success" type="button"  onclick="handler()">Calculate</button>
            </form>
';

include_once __DIR__ . '/Layout/footer.php';
