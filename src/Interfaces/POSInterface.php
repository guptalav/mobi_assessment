<?php
namespace Mobi\Assessment\Interfaces;

interface POSInterface {
    public function getCategories(): array;
    public function getProducts(): array;
    public function getModifiers(): array;
}