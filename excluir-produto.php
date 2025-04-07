<?php

require "src/connectDb.php";
require "src/Model/Produto.php";
require "src/Repository/ProdutoRepositorio.php";

$produtosRepositorio = new ProdutoRepositorio($pdo);
$produtosRepositorio->deletar($_POST["id"]);

header(header: "Location: admin.php");
