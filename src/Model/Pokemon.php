<?php

namespace Model;

class Pokemon implements \JsonSerializable
{
  private $id;
  private $name;
  private $type;
  private $region;
  private $description;
  private $level;

  public function __construct(
    $id = null,
    $name = null,
    $type = null,
    $region = null,
    $description = null,
    $level = null
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->type = $type;
    $this->region = $region;
    $this->description = $description;
    $this->level = $level;
  }

  // Getters
  public function getId()
  {
    return $this->id;
  }
  public function getName()
  {
    return $this->name;
  }
  public function getType()
  {
    return $this->type;
  }
  public function getRegion()
  {
    return $this->region;
  }
  public function getDescription()
  {
    return $this->description;
  }
  public function getLevel()
  {
    return $this->level;
  }

  // Setters
  public function setId($id)
  {
    $this->id = $id;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function setRegion($region)
  {
    $this->region = $region;
  }
  public function setDescription($description)
  {
    $this->description = $description;
  }
  public function setLevel($level)
  {
    $this->level = $level;
  }

  private function createId(): string
  {
    return uniqid();
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'type' => $this->type,
      'region' => $this->region,
      'description' => $this->description,
      'level' => $this->level,
    ];
  }
}
