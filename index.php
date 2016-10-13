<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Microarray Analysis</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Nicholas Stiffler">

        <!-- Le styles -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <style>
            body {
                padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            }
            table td {overflow: hidden;}
        </style>

        
        
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="microarray.js"></script>
        
    </head>

    <body>
        <div class="alert hide fade in" id="test">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
        </div>

        <?php
        $page = "experiment";
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        include("content/" . $page . ".php");
        ?>

      
    </body>
</html>