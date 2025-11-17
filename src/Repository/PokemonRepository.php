<?php

namespace Repository;

use Database\Database;
use Model\Pokemon;
use PDO;

class PokemonRepository
{
  private $connection;

  public function __construct()
  {
    $this->connection = Database::getConnection();
  }

  public function findAll()
  {
    $stmt = $this->connection->prepare("SELECT * from pokemons");
    $stmt->execute();

    $pokemons = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $pokemons[] = new Pokemon(
        $row['id'],
        $row['name'],
        $row['type'],
        $row['region'],
        $row['description'],
        $row['level']
      );
    }

    return $pokemons;
  }

  public function findById($id){
    $stmt = $this->connection->prepare("SELECT * FROM pokemons WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      return new Pokemon(
        $row['id'],
        $row['name'],
        $row['type'],
        $row['region'],
        $row['description'],
        $row['level']
      );
    }
  }
}
