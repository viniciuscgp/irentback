<?php

// routes/usuarios.php

require_once 'database/tables.php';

// Olha o CRUD ai gente!!!
// Create
Flight::route('POST /usuarios', function () {
    CriaTabelas();

    try {
        $db = Flight::db();
        $data = Flight::request()->data->getData();

        $nome = $data['nome'];
        $email = $data['email'];
        $senha = $data['senha'];

        if (empty($nome) || empty($email) || empty($senha)) {
            Flight::json(['status' => 'error', 'message' => 'Todos os campos são obrigatórios.']);
            return;
        }

        $senha = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);

        Flight::json(['status' => 'success', 'message' => 'Usuário criado com sucesso.']);
    } catch (PDOException $e) {
        Flight::json(['status' => 'error', 'message' => 'Erro ao criar usuário: ' . $e->getMessage()]);
    }
});



// Read
Flight::route('GET /usuarios', function () {
    CriaTabelas();

    $db = Flight::db();
    $stmt = $db->prepare("SELECT * FROM usuario");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    Flight::json($usuarios);
});

Flight::route('GET /usuarios/@id', function ($id) {
    CriaTabelas();

    $db = Flight::db();
    $stmt = $db->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        Flight::json($usuario);
    } else {
        Flight::halt(404, 'Usuário não encontrado');
    }
});

// Update
Flight::route('PUT /usuarios/@id', function ($id) {
    CriaTabelas();

    $db = Flight::db();
    $data = Flight::request()->data->getData();
    $stmt = $db->prepare("UPDATE usuario SET email = ?, senha = ? WHERE id = ?");
    $stmt->execute([$data['email'], $data['senha'], $id]);
    Flight::json(['status' => 'success', 'message' => 'Usuário atualizado com sucesso.']);
});

// Delete
Flight::route('DELETE /usuarios/@id', function ($id) {
    CriaTabelas();

    $db = Flight::db();
    $stmt = $db->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    Flight::json(['status' => 'success', 'message' => 'Usuário deletado com sucesso.']);
});

// Auth
Flight::route('POST /usuarios/auth', function () {
    CriaTabelas();

    $questions = [
        '8 + 3', '8 - 3', '1 + 2', '7 x 2', '4 - 1',
        '5 + 1', '8 + 9', '6 + 6', '3 - 3', '1 + 9'
    ];


    $data = Flight::request()->data->getData();

    $captcha_codigo = $data['captcha_codigo'];
    $captcha_resposta = $data['captcha_resposta'];

    $captcha_calculado = evaluate_captcha($questions[$captcha_codigo]);

    // captcha incorreto
    if ((int) $captcha_resposta !== $captcha_calculado) {
        Flight::halt(401, json_encode(['status' => 'error', 'message' => 'Captcha incorreto!']));
    }

    $db = Flight::db();
    $stmt = $db->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$data['email']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Se o usuário existe, verifique a senha
        if (password_verify($data['senha'], $usuario['senha'])) {
            // Senha está correta
            Flight::json([
                'status' => 'success',
                'message' => 'Usuário autenticado com sucesso.',
                'id' => $usuario['id'],
                'nome' => $usuario['nome']
            ]);
        } else {
            // Senha está incorreta
            Flight::halt(401, json_encode(['status' => 'error', 'message' => 'Email ou senha incorretos.']));
        }
    } else {
        // Usuário não encontrado
        Flight::halt(401, json_encode(['status' => 'error', 'message' => 'Email ou senha incorretos.']));
    }
});


function evaluate_captcha($expr)
{
    $parts = explode(' ', $expr);

    $num1 = (int)$parts[0];
    $operator = $parts[1];
    $num2 = (int)$parts[2];

    switch ($operator) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case 'x':
            return $num1 * $num2;
        case '/':
            return $num1 / $num2;
        default:
            throw new Exception('Operador não suportado!');
    }
}
