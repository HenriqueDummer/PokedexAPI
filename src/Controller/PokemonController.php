<?php

namespace Controller;

use Error\APIException;
use Http\Request;
use Http\Response;
use Service\PokemonService;

class PokemonController
{
  private PokemonService $service;

  public function __construct()
  {
    $this->service = new PokemonService();
  }

  public function processRequest(Request $request)
  {
    $id = $request->getId();
    $method = $request->getMethod();

    if ($id === null) {
      switch ($method) {
        case "GET":
          $query = $request->getQuery();
          if (isset($query['region']) || isset($query['regiao'])) {
            $region = $query['region'] ?? $query['regiao'];
            $pokemons = $this->service->getPokemonsByRegion($region);
          } elseif (isset($query['type']) || isset($query['tipo'])) {
            $type = $query['type'] ?? $query['tipo'];
            $pokemons = $this->service->getPokemonsByType($type);
          } else {
            $pokemons = $this->service->getPokemons();
          }
          error_log(print_r($pokemons, true));
          Response::send($pokemons);
          break;
        case "POST":
          $pokemon = $this->validateBody($request->getBody());
          $response = $this->service->createPokmeon(...$pokemon);

          Response::send($response, 201);
          break;

        default:
          throw new APIException("Method not allowed!", 405);
      }
    } else {
      switch ($method) {
        case 'GET':
          $pokemon = $this->service->getPokemonById($id);
          if ($pokemon) {
            Response::send($pokemon);
          } else {
            throw new APIException("Pokémon não encontrado", 404);
          }
          return;
        case 'PUT':
          $pokemonData = $this->validateBody($request->getBody());
          $updated = $this->service->updatePokemon(
            $id,
            $pokemonData['name'],
            $pokemonData['type'],
            $pokemonData['region'],
            $pokemonData['description'],
            intval($pokemonData['level'])
          );
          Response::send($updated);
          return;
        case 'PATCH':
          $body = $request->getBody();
          if (empty($body)) {
            throw new APIException("O corpo da request está vazio", 400);
          }

          $camposAtualizados = ['name', 'type', 'region', 'description', 'level'];
          $updates = [];
          foreach ($body as $campo => $valor) {
            if (in_array($campo, $camposAtualizados)) {
              $updates[$campo] = $valor;
            }
          }

          if (isset($updates['level'])) {
            $updates['level'] = intval($updates['level']);
          }

          $patched = $this->service->patchPokemon($id, $updates);
          Response::send($patched);
          return;
        case 'DELETE':
          $this->service->deletePokemon($id);
          Response::send(null, 204);
          return;
      }
      throw new APIException("Operações com ID não implementadas ainda", 501);
    }
  }

  function validateBody(array $body)
  {
    if (!isset($body["name"]))
      throw new APIException("Propriedade name é obrigatório!", 400);

    if (!isset($body["type"]))
      throw new APIException("Propriedade type é obrigatório!", 400);

    if (!isset($body["region"]))
      throw new APIException("Propriedae region é obrigatório!", 400);

    if (!isset($body["description"]))
      throw new APIException("Propriedade description é obrigatório!", 400);

    if (!isset($body["level"]))
      throw new APIException("Propriedade level é obrigatório!", 400);

    return $body;
  }
}
