<?php

namespace Mobi\Assessment\Models;

class CategoryModel {
    private string $name;
    private array $products;
    private int $id;

    public function __construct(int $id, string $name, array $products)
    {
        $this->name = $name;
        $this->id = $id;
        $this->products = $products;
    }

    /**
     * Returns the name of the category
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the list of products in the category
     *
     * @return ProductModel[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Returns the id of the category
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}