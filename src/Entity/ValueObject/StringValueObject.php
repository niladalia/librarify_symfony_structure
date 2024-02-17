<?php

namespace App\Entity\ValueObject;

// La fem abstract perque no volem que sigui instanciable i perque conté u metode abstracte a mes a mes de metodes implementats
abstract class StringValueObject implements Field
{
    protected ?string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value;
        $this->validate();
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
    //Obliga a que tothom que heredi daquesta clase, implementi el validate()
    abstract protected function validate();
}
