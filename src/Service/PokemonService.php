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

  function getPokemonsByRegion(string $region)
  {
    return $this->repository->findByRegion(trim($region));
  }

  function getPokemonsByType(string $type)
  {
    return $this->repository->findByType(trim($type));
  }

  function getPokemonById($id)
  {
    $pokemon =  $this->repository->findById($id);
    if (!$pokemon) {
      throw new APIException("Pokemon não encontrado", 404);
    }

    return $pokemon;
  }

  function createPokmeon(string $name, string $type, string $region, string $description, int $level)
  {
    $pokemon = new Pokemon(name: trim($name), type: trim($type), region: trim($region), description: $description, level: $level);
    $this->validatePokemon($pokemon);
    
    try {
      return $this->repository->create($pokemon);
    } catch (\PDOException $error) {
      if ($error->getCode() == '23000') {
        throw new APIException("Um Pokémon com esse nome já existe", 409);
      }
      throw $error;
    }
  }

  function updatePokemon($id, string $name, string $type, string $region, string $description, int $level)
  {
    $existing = $this->repository->findById($id);
    if (!$existing) {
      throw new APIException("Pokemon não encontrado", 404);
    }

    $pokemon = new Pokemon($id, trim($name), trim($type), trim($region), $description, $level);
    $this->validatePokemon($pokemon);

    return $this->repository->update($pokemon);
  }

  function patchPokemon($id, array $fields)
  {
    $existing = $this->repository->findById($id);
    if (!$existing) {
      throw new APIException("Pokemon não encontrado", 404);
    }

    if (isset($fields['name'])) $existing->setName(trim($fields['name']));
    if (isset($fields['type'])) $existing->setType(trim($fields['type']));
    if (isset($fields['region'])) $existing->setRegion(trim($fields['region']));
    if (isset($fields['description'])) $existing->setDescription($fields['description']);
    if (isset($fields['level'])) $existing->setLevel(intval($fields['level']));

    $this->validatePokemon($existing);

    return $this->repository->partialUpdate($id, $fields);
  }

  function deletePokemon($id)
  {
    $existing = $this->repository->findById($id);
    if (!$existing) {
      throw new APIException("Pokemon não encontrado", 404);
    }

    $this->repository->delete($id);
  }

  function validatePokemon(Pokemon $pokemon)
  {
    if (strlen(trim($pokemon->getName())) < 3) {
      throw new APIException("O nome do Pokémon deve ter mais de 3 letras");
    }

    if (strlen(trim($pokemon->getRegion())) === 0) {
      throw new APIException("A região Pokémon não pode estar vazia");
    }

    if (strlen(trim($pokemon->getType())) === 0) {
      throw new APIException("O tipo Pokémon não pode estar vazio");
    }

    if (strlen(trim($pokemon->getDescription())) === 0) {
      throw new APIException("A descrição do Pokémon não pode estar vazia");
    }

    if ($pokemon->getLevel() < 0 || $pokemon->getLevel() > 100) {
      throw new APIException("O nível deve ser um valor entre 1 e 100", 400);
    }
  }
}
