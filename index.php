<?php
use controllers\Fornecedor;
use Slim\Slim;
$loader = require 'vendor/autoload.php';

$app = new Slim(array(
    'templates.path' => 'templates'
));
//listando todos
$app->get("/fornecedor/", function() use ($app){
    (new Fornecedor($app))->lista();
});
//listando um
$app->get("/fornecedor/:id", function($id) use ($app){
    (new Fornecedor($app))->get($id);
});
//cadastro - nova pessoa
$app->post("/fornecedor/", function() use ($app){
    (new Fornecedor($app))->nova();
});
//editando pessoa
$app->put("/fornecedor/:id", function($id) use ($app){
    (new Fornecedor($app))->editar($id);
});
//apagando pessoa
$app->delete("/fornecedor/:id", function($id) use ($app){
    (new Fornecedor($app))->excluir($id);
});

$app->get("/", function () {
echo "Para Fornecedores:  <a href='/restsysfornecedor/index.php/fornecedor/'>/link/</a> ";
});



$app->run();


