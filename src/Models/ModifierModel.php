<?php

namespace Mobi\Assessment\Models;

class ModifierModel {
    private string $name;
    private array $products;
    private int $id;

    public function __construct(int $id, string $name, array $products)
    {
        $this->id = $id;
        $this->name = $name;
        $this->products = $products;
    }

    /**
     * Returns the name of the modifier
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the id of the modifier
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the list of products in the modifier
     *
     * @return ProductModel[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}