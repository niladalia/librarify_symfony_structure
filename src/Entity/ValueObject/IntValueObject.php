<?php

namespace App\Entity\ValueObject;

// La fem abstract perque no volem que sigui instanciable i perque conté u metode abstracte a mes a mes de metodes implementats
abstract class IntValueObject implements Field{

    protected ?int $value;
    
    public function __construct(?int $value = null) 
    {
        $this->value = $value;
        $this->validate();
    }
    
    public function getValue(): ?int
    {
        return $this->value;
    }
    
    //Obliga a que tothom que heredi daquesta clase, implementi el validate()
    protected abstract function validate();
}