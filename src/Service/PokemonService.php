<?php

namespace Service;

use Error\APIException;
use Model\Pokemon;
use Repository\PokemonRepository;

class PokemonService
{
  private PokemonRepository $repository;

  function __construct()
  {
    $this->repository = new PokemonRepository();
  }

  function getPokemons()
  {
    return $this->repository->findAll();
  }

  function getPokemonById($id)
  {
    $pokemon =  $this->repository->findById($id);
    if (!$pokemon) {
      throw new APIException("Pokemon not found", 404);
    }

    return $pokemon;
  }

  function createPokmeon(string $name, string $type, string $region, string $description, int $level)
  {
    $pokemon = new Pokemon(name: trim($name), type: trim($type), region: trim($region), description: $description, level: $level);
    $this->validatePokemon($pokemon);
    return $this->repository->create($pokemon);
  }

  function validatePokemon(Pokemon $pokemon)
  {
    if (strlen(trim($pokemon->getName())) < 3) {
      throw new APIException("Pokemon name must have more than 3 letters");
    }

    if (strlen(trim($pokemon->getRegion())) === 0) {
      throw new APIException("Pokemon region cannot be empty");
    }

    if (strlen(trim($pokemon->getType())) === 0) {
      throw new APIException("Pokemon type cannot be empty");
    }

    if (strlen(trim($pokemon->getDescription())) === 0) {
      throw new APIException("Pokemon description cannot be empty");
    }

    if ($pokemon->getLevel() < 0) {
      throw new APIException("Level must be greater than 0", 400);
    }
  }
}
