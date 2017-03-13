<?php
use controllers\Pessoa;
use Slim\Slim;
$loader = require 'vendor/autoload.php';

$app = new Slim(array(
    'templates.path' => 'templates'
));
//listando todos
$app->get("/pessoas/", function() use ($app){
    (new Pessoa($app))->lista();
});
//listando um
$app->get("/pessoas/:id", function($id) use ($app){
    (new Pessoa($app))->get($id);
});
//cadastro - nova pessoa
$app->post("/pessoas/", function() use ($app){
    (new Pessoa($app))->nova();
});
//editando pessoa
$app->put("/pessoas/:id", function($id) use ($app){
    (new Pessoa($app))->editar($id);
});
//apagando pessoa
$app->delete("/pessoas/:id", function($id) use ($app){
    (new Pessoa($app))->excluir($id);
});

$app->get("/", function () {
echo "SlimProdutos ";
});



$app->run();


