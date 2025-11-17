<?php

namespace Service;
use Repository\PokemonRepository;

class PokemonService
{
  private PokemonRepository $repository;

  function __construct()
  {
    $this->repository = new PokemonRepository();
  }

  function getPokemons(){
    return $this->repository->findAll();
  }

  function getPokemonById($id){
    return $this->repository->findById($id);
  }
}
