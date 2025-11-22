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

  public function findById($id)
  {
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

  public function create(Pokemon $pokemon)
  {
    $stmt = $this->connection->prepare("INSERT INTO pokemons (name, type, region, description, level) VALUES (:name, :type, :region, :description, :level)");
    $stmt->bindValue(':name', $pokemon->getName());
    $stmt->bindValue(':type', $pokemon->getType());
    $stmt->bindValue(':region', $pokemon->getRegion());
    $stmt->bindValue(':description', $pokemon->getDescription());
    $stmt->bindValue(':level', $pokemon->getLevel(), PDO::PARAM_INT);
    $stmt->execute();

    $pokemon->setId($this->connection->lastInsertId());
    return $pokemon;
  }
}
