<?php
require_once 'database/tables.php';

// Olha ai o CRUD rsrsr
// Create
Flight::route('POST /imoveis', function () {
    CriaTabelas();
    $db = Flight::db();
    $data = Flight::request()->data->getData();
    $stmt = $db->prepare("INSERT INTO imovel (resumo, descricao, tipo, quartos, metragem, cidade, bairro, valor, imagens) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$data['resumo'], $data['descricao'], $data['tipo'], $data['quartos'], $data['metragem'], $data['cidade'], $data['bairro'], $data['valor'], $data['imagens']]);
    Flight::json(['status' => 'success', 'message' => 'Imóvel criado com sucesso.']);
});

// Read
Flight::route('GET /imoveis', function () {
    CriaTabelas();

    $db = Flight::db();

    // Armazenamos os valores dos parâmetros GET em variáveis
    $descricao = '%' . Flight::request()->query['descricao'] . '%';
    $cidade = Flight::request()->query['cidade'];
    $bairro = Flight::request()->query['bairro'];
    $quartos = Flight::request()->query['quartos'];

    // Parâmetros de paginação
    $limit = Flight::request()->query['limit'] ?? 5; // Padrão é 5 se não fornecido
    $pagina = Flight::request()->query['pagina'];

    if (isset($pagina)) {
        $offset = ($pagina - 1) * $limit;
    } else {
        $offset = Flight::request()->query['offset'] ?? 0; // Padrão é 0 se não fornecido
    }


    // Preparamos a consulta SQL para buscar o total de registros
    $sqlTotal = "SELECT COUNT(*) as total FROM imovel WHERE (descricao LIKE :descricao OR :descricao IS NULL)
            AND (cidade = :cidade OR :cidade IS NULL)
            AND (bairro = :bairro OR :bairro IS NULL)
            AND (quartos >= :quartos OR :quartos IS NULL)";

    $stmtTotal = $db->prepare($sqlTotal);
    $stmtTotal->bindParam(':descricao', $descricao);
    $stmtTotal->bindParam(':cidade', $cidade);
    $stmtTotal->bindParam(':bairro', $bairro);
    $stmtTotal->bindParam(':quartos', $quartos);
    $stmtTotal->execute();
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Preparamos a consulta SQL para buscar os imóveis
    $sql = "SELECT * FROM imovel WHERE (descricao LIKE :descricao OR :descricao IS NULL)
            AND (cidade = :cidade OR :cidade IS NULL)
            AND (bairro = :bairro OR :bairro IS NULL)
            AND (quartos = :quartos OR :quartos IS NULL)
            LIMIT :limit OFFSET :offset";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':quartos', $quartos);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT); // Tipo de dados precisa ser int
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT); // Tipo de dados precisa ser int
    $stmt->execute();
    $imoveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculamos o número da página atual
    $page = floor($offset / $limit) + 1;

    // Preparamos a resposta
    $response = [
        'page' => $page,
        'pageSize' => $limit,
        'total' => $total,
        'items' => $imoveis,
    ];

    Flight::json($response);
});




Flight::route('GET /imoveis/@id', function ($id) {
    CriaTabelas();

    // Define os cabeçalhos CORS
    header("Access-Control-Allow-Origin: *"); // Permite todas as origens !!!!.
    header("Access-Control-Allow-Methods: GET"); // Métodos HTTP permitidos
    header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Headers permitidos

    $db = Flight::db();
    $stmt = $db->prepare("SELECT * FROM imovel WHERE id = ?");
    $stmt->execute([$id]);
    $imovel = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($imovel) {
        Flight::json($imovel);
    } else {
        Flight::halt(404, 'Imóvel não encontrado');
    }
});

// Update
Flight::route('PUT /imoveis/@id', function ($id) {
    CriaTabelas();
    $db = Flight::db();
    $data = Flight::request()->data->getData();
    $stmt = $db->prepare("UPDATE imovel SET resumo = ?, descricao = ?, tipo = ?, quartos = ?, metragem = ?, cidade = ?, bairro = ?, valor = ?, imagens = ? WHERE id = ?");
    $stmt->execute([$data['resumo'], $data['descricao'], $data['tipo'], $data['quartos'], $data['metragem'], $data['cidade'], $data['bairro'], $data['valor'], $data['imagens'], $id]);
    Flight::json(['status' => 'success', 'message' => 'Imóvel atualizado com sucesso.']);
});

// Delete
Flight::route('DELETE /imoveis/@id', function ($id) {
    CriaTabelas();
    $db = Flight::db();
    $stmt = $db->prepare("DELETE FROM imovel WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json(['status' => 'success', 'message' => 'Imóvel deletado com sucesso.']);
});
