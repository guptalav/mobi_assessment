<?php
namespace Mobi\Assessment\Models;

// removed Id and Modifiers from ProductModel after discussions
class ProductModel {
    private array $prices;
    private string $name;
    private string $plu;
    private string $backend_name;

    public function __construct(string $plu, string $name, string $backend_name, array $prices)
    {
        $this->name = $name;
        $this->plu = $plu;
        $this->backend_name = $backend_name;
        $this->prices = $prices;
    }

    /**
     * Returns the name of the product
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the plu of the product
     *
     * @return string
     */
    public function getPlu(): string
    {
        return $this->plu;
    }

    /**
     * Returns the backend name of the product
     *
     * @return string
     */
    public function getBackendName(): string
    {
        return $this->backend_name;
    }

    /**
     * Returns the prices of the product
     *
     * @return float[]
     */
    public function getPrices(): array
    {
        return $this->prices;
    }
}