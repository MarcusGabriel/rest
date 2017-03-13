<?php

namespace controllers;
use PDO;

class Pessoa{

    private $PDO;

    public function __construct()
    {
        $this->PDO = new PDO('mysql:host=localhost;dbname=rest', 'root', '');
        $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    public function lista(){
        global $app;
        $stmt = $this->PDO->prepare('SELECT * FROM pessoa');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);            
        $app->render('default.php', ["data" => $result], 200);
    }

    public function get($id)
    {
        global $app;
        $stmt = $this->PDO->prepare("SELECT * FROM pessoa WHERE id=:id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $app->render("default.php", ["data" => $result], 200);
    }

    public function nova()
    {
        global $app;
        $dados = json_decode($app->request->getBody(), true);
        $dados = (sizeof($dados)==0) ? $_POST : $dados;
        $keys = array_keys($dados);
        $query = "INSERT INTO pessoa (".implode(",", $keys).") VALUES(:".implode(",:",$keys).")";        
        $stmt = $this->PDO->prepare($query);
        foreach ($dados as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        $app->render('default.php', ["data" => ['id' => $this->PDO->lastInsertId()]], 200);
    }

    public function editar($id)
    {
        global $app;
        $dados = json_decode($app->request->getBody(), true);
        $dados = (sizeof($dados)==0) ? $_POST : $dados;
        $sets = [];
        foreach ($dados as $key => $VALUE) {
            $sets[] = $key." = :".$key;
        }
        $stmt = $this->PDO->prepare("UPDATE pessoa SET ".implode(",", $sets)." WHERE id = :id");
        $stmt->bindValue(':id', $id);        
        foreach ($dados as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        $app->render('default.php', ["data" => ['status' => $stmt->execute()==1]], 200);
    }

    public function excluir($id)
    {
        global $app;
        $stmt = $this->PDO->prepare("DELETE FROM pessoa WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $app->render('default.php', ["data" => ['status' => $stmt->execute()==1]], 200);
    }
}