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

  public function findByRegion(string $region)
  {
    $stmt = $this->connection->prepare("SELECT * FROM pokemons WHERE region = :region");
    $stmt->bindValue(':region', $region);
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

  public function findByType(string $type)
  {
    $stmt = $this->connection->prepare("SELECT * FROM pokemons WHERE type = :type");
    $stmt->bindValue(':type', $type);
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

  public function update(Pokemon $pokemon)
  {
    $stmt = $this->connection->prepare("UPDATE pokemons SET name = :name, type = :type, region = :region, description = :description, level = :level WHERE id = :id");
    $stmt->bindValue(':name', $pokemon->getName());
    $stmt->bindValue(':type', $pokemon->getType());
    $stmt->bindValue(':region', $pokemon->getRegion());
    $stmt->bindValue(':description', $pokemon->getDescription());
    $stmt->bindValue(':level', $pokemon->getLevel(), PDO::PARAM_INT);
    $stmt->bindValue(':id', $pokemon->getId(), PDO::PARAM_INT);
    $stmt->execute();

    return $pokemon;
  }

  public function partialUpdate($id, array $campos)
  {
    $campoAtualizado = ['name', 'type', 'region', 'description', 'level'];

    $updates = [];
    foreach ($campos as $campo => $valor) {
      if (in_array($campo, $campoAtualizado)) {
        $updates[$campo] = $valor;
      }
    }

    if (empty($updates)) {
      return $this->findById($id);
    }

    $setClauses = [];
    $params = [];
    foreach ($updates as $col => $val) {
      $setClauses[] = "{$col} = :{$col}";
      $params[":{$col}"] = $val;
    }

    $setSql = implode(', ', $setClauses);
    $sql = "UPDATE pokemons SET {$setSql} WHERE id = :id";

    $stmt = $this->connection->prepare($sql);
    foreach ($params as $param => $value) {
      if ($param === ':level') {
        $stmt->bindValue($param, intval($value), PDO::PARAM_INT);
      } else {
        $stmt->bindValue($param, $value);
      }
    }

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $this->findById($id);
  }

  public function delete($id)
  {
    $stmt = $this->connection->prepare("DELETE FROM pokemons WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount() > 0;
  }
}
