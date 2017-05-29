<?php

namespace controllers;
use controllers\Database\Database;
use PDO;

class Fornecedor{

    private $PDO;
    private $table = "fornecedores";
    public function __construct()
    {
        $this->PDO = Database::getConnection();
    }


    public function lista(){
        global $app;
        $stmt = $this->PDO->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);            
        $app->render('default.php', ["data" => $result], 200);
    }

    public function get($id)
    {
        global $app;
        $stmt = $this->PDO->prepare("SELECT * FROM $this->table WHERE forid=:id");
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
        $query = "INSERT INTO $this->table (".implode(",", $keys).") VALUES(:".implode(",:",$keys).")";        
        
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
        $stmt = $this->PDO->prepare("UPDATE $this->table SET ".implode(",", $sets)." WHERE forid = :id");
        $stmt->bindValue(':id', $id);        
        foreach ($dados as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        $app->render('default.php', ["data" => ['status' => $stmt->execute()==1]], 200);
    }

    public function excluir($id)
    {
        global $app;
        $stmt = $this->PDO->prepare("DELETE FROM $this->table WHERE forid = :id");
        $stmt->bindValue(":id", $id);
        $app->render('default.php', ["data" => ['status' => $stmt->execute()==1]], 200);
    }
}