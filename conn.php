<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 21/01/19
 * Time: 17:40
 */

try {
    $pdo = new PDO('mysql:host=localhost;dbname=temigrei', 'root', 'nheac4257');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}