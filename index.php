<?php
require_once 'src/config.php';

use Controller\PokemonController;
use Http\Request;
use Http\Response;
use Error\APIException;

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER["REQUEST_METHOD"];
$body = file_get_contents("php://input");
$request = new Request($uri, $method, $body);

switch ($request->getResource()) {
  case "pokemons":
    $pokemonsController = new PokemonController();
    $pokemonsController->processRequest($request);
    break;
  case "setup.php":
    require_once './src/Database/setup.php';
    break;
  case null:
    Response::send([
      'autores' => [
        'Andreos Henrique',
        'Gabriel Huff'
      ],
      'descricao' => 'API para gerenciamento de Pokémon',
      'rotas' => [
        'GET /' => 'Informações da API',
        'GET /api/pokemons' => 'Lista todos os Pokémon',
        'GET /api/pokemons?tipo=Fogo' => 'Filtra Pokémon por tipo',
        'GET /api/pokemons?regiao=Kanto' => 'Filtra Pokémon por região',
        'GET /api/pokemons/{id}' => 'Retorna um Pokémon específico',
        'POST /api/pokemons' => 'Cria um novo Pokémon',
        'PUT /api/pokemons/{id}' => 'Atualiza completamente um Pokémon',
        'PATCH /api/pokemons/{id}' => 'Atualiza parcialmente um Pokémon',
        'DELETE /api/pokemons/{id}' => 'Remove um Pokémon'
      ],
    ]);
    break;

  default:
    // Recurso não encontrado
    throw new APIException("Recurso não encontrado: {$request->getResource()}", 404);
}
