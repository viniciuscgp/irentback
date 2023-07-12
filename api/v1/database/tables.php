<?php

function CriaTabelas()
{
    $db = Flight::db();

    $usuariosTable = "
    CREATE TABLE IF NOT EXISTS usuario (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        nome VARCHAR(255) NOT NULL,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";


    $db->exec($usuariosTable);

    $imoveisTable = "
            CREATE TABLE IF NOT EXISTS imovel (
                id INT AUTO_INCREMENT PRIMARY KEY,
                resumo VARCHAR(255),
                descricao TEXT,
                tipo VARCHAR(50),
                quartos INT,
                metragem VARCHAR(100),
                cidade VARCHAR(100),
                bairro VARCHAR(100),
                valor DECIMAL(10, 2),
                imagens TEXT,
                usuario_id INT,
                FOREIGN KEY (usuario_id) REFERENCES usuario (id)                
            )";
    $db->exec($imoveisTable);
}
