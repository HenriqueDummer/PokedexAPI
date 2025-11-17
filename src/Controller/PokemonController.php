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
          $pokemons = $this->service->getPokemons();
          error_log(print_r($pokemons, true));
          Response::send($pokemons);
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
      }
      throw new APIException("Operações com ID não implementadas ainda", 501);
    }
  }
}
