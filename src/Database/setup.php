<?php

$database = __DIR__ . '/database.sqlite';

try {
  $conn = new PDO("sqlite:" . $database);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec("PRAGMA foreign_keys = ON;");
  echo "Conexão com o banco de dados estabelecida com sucesso!" . PHP_EOL;
} catch (Exception $e) {
  echo "Erro ao conectar ao banco de dados: " . $e->getMessage() . PHP_EOL;
  exit;
}

try {
  $conn->exec("DROP TABLE IF EXISTS pokemons;");

  $sql = "CREATE TABLE pokemons(
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              name TEXT NOT NULL,
              type TEXT NOT NULL,
              region TEXT NOT NULL,
              description TEXT NOT NULL,
              level INTEGER NOT NULL
   )";

  $conn->exec($sql);

  echo "Tabelas criadas!\n";
} catch (PDOException $e) {
  echo "Erro ao criar as tabelas: " . $e->getMessage() . PHP_EOL;
  exit;
}

$pokemons = [
  [
    'name' => 'Pikachu',
    'type' => 'Elétrico',
    'region' => 'Kanto',
    'description' => 'Um Pokémon elétrico adorável que armazena energia em suas bochechas.',
    'level' => 25
  ],
  [
    'name' => 'Charizard',
    'type' => 'Fogo/Voador',
    'region' => 'Kanto',
    'description' => 'Um poderoso dragão de fogo que cospe chamas intensas.',
    'level' => 65
  ],
  [
    'name' => 'Blastoise',
    'type' => 'Água',
    'region' => 'Kanto',
    'description' => 'Um Pokémon tartaruga com canhões de água em seu casco.',
    'level' => 60
  ],
  [
    'name' => 'Venusaur',
    'type' => 'Planta/Veneno',
    'region' => 'Kanto',
    'description' => 'Uma planta gigante com uma flor nas costas que libera um aroma calmante.',
    'level' => 55
  ],
  [
    'name' => 'Mewtwo',
    'type' => 'Psíquico',
    'region' => 'Kanto',
    'description' => 'Um Pokémon lendário criado geneticamente com poderes psíquicos extraordinários.',
    'level' => 100
  ],
  [
    'name' => 'Lugia',
    'type' => 'Psíquico/Voador',
    'region' => 'Johto',
    'description' => 'O guardião dos mares, capaz de controlar tempestades.',
    'level' => 100
  ],
  [
    'name' => 'Typhlosion',
    'type' => 'Fogo',
    'region' => 'Johto',
    'description' => 'Um Pokémon de fogo com chamas que emanam de seu pescoço.',
    'level' => 58
  ],
  [
    'name' => 'Feraligatr',
    'type' => 'Água',
    'region' => 'Johto',
    'description' => 'Um jacaré feroz com mandíbulas poderosas.',
    'level' => 58
  ],
  [
    'name' => 'Rayquaza',
    'type' => 'Dragão/Voador',
    'region' => 'Hoenn',
    'description' => 'Um dragão lendário que vive na camada de ozônio.',
    'level' => 100
  ],
  [
    'name' => 'Garchomp',
    'type' => 'Dragão/Terra',
    'region' => 'Sinnoh',
    'description' => 'Um Pokémon dragão extremamente rápido e forte.',
    'level' => 78
  ],
  [
    'name' => 'Lucario',
    'type' => 'Lutador/Aço',
    'region' => 'Sinnoh',
    'description' => 'Um Pokémon que pode sentir e manipular aura.',
    'level' => 55
  ],
  [
    'name' => 'Greninja',
    'type' => 'Água/Sombrio',
    'region' => 'Kalos',
    'description' => 'Um ninja ágil que ataca com precisão e velocidade.',
    'level' => 60
  ],
  [
    'name' => 'Decidueye',
    'type' => 'Planta/Fantasma',
    'region' => 'Alola',
    'description' => 'Um arqueiro fantasma que ataca com penas afiadas.',
    'level' => 58
  ],
  [
    'name' => 'Cinderace',
    'type' => 'Fogo',
    'region' => 'Galar',
    'description' => 'Um Pokémon jogador de futebol com chutes flamejantes.',
    'level' => 60
  ],
  [
    'name' => 'Eevee',
    'type' => 'Normal',
    'region' => 'Kanto',
    'description' => 'Um Pokémon com DNA instável que pode evoluir de várias formas.',
    'level' => 20
  ]
];

try {
  $conn->beginTransaction();

  $sql = "INSERT INTO pokemons (name, type, region, description, level) 
            VALUES (:name, :type, :region, :description, :level);";

  $stmt = $conn->prepare($sql);
  foreach ($pokemons as $pokemon) {
    $stmt->execute($pokemon);
  }
  echo "Pokemons inseridos com sucesso!" . PHP_EOL;

  $conn->commit();
  echo "Banco de dados configurado com sucesso!\n" . PHP_EOL;
} catch (PDOException $e) {
  if ($conn->inTransaction()) $conn->rollBack();
  echo "Erro ao inserir os dados: " . $e->getMessage() . PHP_EOL;
  exit;
}
