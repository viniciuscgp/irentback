<?php
require 'vendor/autoload.php';

Flight::map('header', function () {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
});

Flight::before('start', function (&$params, &$output) {
    Flight::route('OPTIONS *', function () {
    });
    Flight::header();
});


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV["DB_USERNAME"];
$password = $_ENV['DB_PASSWORD'];

Flight::register('db', 'PDO', array('mysql:host=' . $host . ';dbname=' . $database, $username, $password));
