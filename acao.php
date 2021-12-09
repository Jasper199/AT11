<?php 

	include_once "conf/default.inc.php";
    require_once "conf/Conexao.php";

    $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if ($acao == 'comprar') {
    	comprar($id);
    } else if ($acao == 'adicionar') {
    	adicionarShow();
    } else if ($acao == 'editar') {
    	editarShow($id);
    } else if ($acao == 'excluir') {
    	excluirShow($id);
    }

    function comprar($id) {
    	session_start();
    	$pdo = Conexao::getInstance();
    	$consulta = $pdo->query("SELECT * FROM shows WHERE id = '$id'");
    	while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        	$preco = $linha['preco'];
        	$qnt_ingressos = $linha['qnt_ingressos'];  
        }
        $qnt_ingressos -= 1;
    	$sql = 'INSERT INTO ingressos (preco, data_compra, contas_id, shows_id) VALUES (:preco, CURRENT_DATE(), :contas_id, :shows_id)';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
		$stmt->bindParam(':contas_id', $_SESSION['id'], PDO::PARAM_STR);
	 	$stmt->bindParam(':shows_id', $id, PDO::PARAM_STR);
		$stmt->execute();
		$sql = "UPDATE shows SET qnt_ingressos='$qnt_ingressos' WHERE id = '$id'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		header('location:ingressos.php');
    }

    function adicionarShow() {
    	$pdo = Conexao::getInstance();
    	$sql = 'INSERT INTO shows (descricao, qnt_ingressos, data, local, preco) VALUES (:descricao, :qnt_ingressos, :data, :local, :preco)';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':descricao', $_POST['descricao'], PDO::PARAM_STR);
    	$stmt->bindParam(':qnt_ingressos', $_POST['qnt_ingressos'], PDO::PARAM_STR);
    	$stmt->bindParam(':data', $_POST['data'], PDO::PARAM_STR);
    	$stmt->bindParam(':local', $_POST['local'], PDO::PARAM_STR);
    	$stmt->bindParam(':preco', $_POST['preco'], PDO::PARAM_STR);
    	$stmt->execute();
    	header('location:organizador.php');
    }

    function editarShow($id) {
    	$pdo = Conexao::getInstance();
    	$descricao = $_POST['descricao'];
    	$qnt_ingressos = $_POST['qnt_ingressos'];
    	$data = $_POST['data'];
    	$local = $_POST['local'];
    	$preco = $_POST['preco'];
    	$sql = "UPDATE shows SET descricao='$descricao', qnt_ingressos='$qnt_ingressos', local='$local', preco='$preco' WHERE id = '$id'";
    	$stmt = $pdo->prepare($sql);
    	$stmt->execute();
    	header('location:organizador.php');
    }

    function excluirShow($id) {
    	$pdo = Conexao::getInstance();
    	$sql = "DELETE FROM shows WHERE id = $id";
    	$stmt = $pdo->prepare($sql);
    	$stmt->execute();
    	header('location:organizador.php');
    }

 ?>