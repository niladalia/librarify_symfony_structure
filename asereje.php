<?php

class StringValue
{
    // $value es accesible también en las subclases
    protected string $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public function __toString() {
        // $this::class muestra la clase del objeto instanciado
        return $this::class . ': ' . $this->value;
    }

    public function toAbstractString(): string {
        // self::class muestra la clase StringValue (equivalente a StringValue::class)
        return self::class . ': ' . $this->value;
    }
}

class Title extends StringValue
{

}

class Description extends StringValue
{
    public function length(): int {
        return strlen($this->value);
    }
}

class Book
{
    private Title $title;
    private Description $description;

    public function __construct(string $title, string $description) {
        $this->title = new Title($title);
        $this->description = new Description($description);
    }

    public function getTitle(): Title {
        return $this->title;
    }

    public function getDescription(): StringValue { // Solo como demostración, aquí no pongo Description sino StringValue
        return $this->description;
    }

    public function __toString() {
        return $this->title . PHP_EOL . $this->description;
    }
}

$book = new Book(
    "El poder del ahora",
    "Un guía espiritual enseña a vivir el presente y liberarse del sufrimiento mental a través de la práctica de la atención plena."
);

// echo $book llama al __toString de Book
echo $book . PHP_EOL . PHP_EOL;

echo get_debug_type($book->getTitle()) . PHP_EOL;

echo $book->getTitle()->toAbstractString() . PHP_EOL . PHP_EOL;

echo get_debug_type($book->getDescription()) . PHP_EOL;

// Esto en un lenguaje fuertemente tipado como Java no funciona (getDescription debería enmascarar el tipo como StringValue)
// Pero PHP es un lenguaje de tipado débil así que solo se fija en si existe el método length en la instancia sin tener en cuenta ningún tipo
echo 'length: ' . $book->getDescription()->length();
