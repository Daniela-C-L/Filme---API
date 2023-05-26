<?php
//-----------------------------------         FILMES            -----------------------------------------

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

// GET == recebe informações
// POST == envia informações
// PUT == edita informações "update"
// DELETE == deleta informações
// OPTIONS == relações de metodos disponiveis para uso

header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // criando comando de select para consultar o banco
    $stmt = $conn->prepare('SELECT * FROM filmes');

    //executando o select
    $stmt->execute();

    //recebdno dados do banco com PDO
    $filmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // tranformando dados em JSON
    echo json_encode($filmes);
}

// INSERÇÃO DE DADOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $genero = $_POST['genero'];
    $ano_lancamento = $_POST['ano_lancamento'];

    $stmt = $conn->prepare('INSERT INTO filmes(titulo, diretor, genero, ano_lancamento) VALUES (:titulo, :diretor, :genero, :ano_lancamento)');

    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':diretor', $diretor);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':ano_lancamento', $ano_lancamento);

    if ($stmt->execute()) {
        echo 'Filme criado com sucesso';
    } else {
        echo 'Erro ao criar um filme';
    }
}

if($_SERVER['REQUEST_METHOD']=== 'DELETE' && isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM filmes WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if($stmt->execute()){
        echo"excluiu";
    } else {
        echo"excluiu não";
    }
}

if($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])){
    parse_str(file_get_contents("php://input"), $_PUT);

    $id = $_GET['id'];
    $novoTitulo = $_PUT['titulo'];
    $novoDiretor = $_PUT['diretor'];
    $novoGenero = $_PUT['genero'];
    $novoAno = $_PUT['ano_lancamento'];
    //add novos campos se necessario

    $stmt = $conn->prepare("UPDATE filmes SET titulo = :titulo, diretor = :diretor, ano_lancamento = :ano_lancamento WHERE id = :id");

    $stmt->bindParam(':titulo', $novoTitulo);
    $stmt->bindParam(':diretor', $novoDiretor);
    $stmt->bindParam(':genero', $novoGenero);
    $stmt->bindParam(':ano_lancamento', $novoAno);
    $stmt->bindParam(':id', $id);
    //add novos campos se necessario

    if($stmt->execute()){
        echo "Filme atualizado com sucesso";
    } else {
        echo "Erro ao atualizar o Filme";
    }
}