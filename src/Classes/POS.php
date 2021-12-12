<?php

namespace Mobi\Assessment\Classes;

use SimpleXMLElement;

use Mobi\Assessment\Classes\Connection\POSXMLConnect;
use Mobi\Assessment\Exceptions\MobiException;
use Mobi\Assessment\Interfaces\POSInterface;
use Mobi\Assessment\Models\CategoryModel;
use Mobi\Assessment\Models\ModifierModel;
use Mobi\Assessment\Models\ProductModel;

class POS implements POSInterface {
    private SimpleXMLElement $data;

    public function __construct(POSXMLConnect $pos_xml_connect)
    {
        try {
            $this->data = $pos_xml_connect->getData();
        } catch (MobiException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Gets all the categories with products from POS
     *
     * @return CategoryModel[]
     * @throws MobiException
     */
    public function getCategories(): array
    {
        $categories = [];
        $categories_list = $this->data->category_group_list;

        $products_map = $this->createProductsMap($this->getProducts());

        if (empty($categories_list)) {
            throw new MobiException('No Categories found');
        }

        foreach ($categories_list->category_group as $category) {
            $category_products = [];
            foreach ($category->cond_plu_code as $cond_plu_code) {
                $plu_code = (string) $cond_plu_code;
                if (!empty($products_map[$plu_code])) {
                    array_push($category_products, $products_map[$plu_code]);
                } else {
                    throw new MobiException("No Product with plu $plu_code found");
                }
            }

            $category_model = new CategoryModel(intval($category['num']), $category['name'], $category_products);
            array_push($categories, $category_model);
        }

        return $categories;
    }

    /**
     * Gets all the products from POS
     *
     * @return ProductModel[]
     * @throws MobiException
     */
    public function getProducts(): array
    {
        $products = [];
        $products_list = $this->data->plu_list->plu;

        if (empty($products_list)) {
            throw new MobiException('No Products found');
        }

        foreach ($products_list as $product) {
            $prices = [];

            foreach ($product->price as $price) {
                if (intval($price) > 0) {
                    array_push($prices, floatval(intval($price)/100));
                }
            }

            $product_model = new ProductModel($product['code'], $product['name'], $product['name2'], $prices);
            array_push($products, $product_model);
        }

        return $products;
    }

    /**
     * Gets all the modifiers with products from POS
     *
     * @return ModifierModel[]
     * @throws MobiException
     */
    public function getModifiers(): array
    {
        $modifiers = [];
        $modifiers_list = $this->data->modifier_group_list;

        $products_map = $this->createProductsMap($this->getProducts());

        if (empty($modifiers_list)) {
            throw new MobiException('No Modifiers found');
        }

        foreach ($modifiers_list->modifier_group as $modifier) {
            $modifier_products = [];
            foreach ($modifier->cond_plu_code as $cond_plu_code) {
                $plu_code = (string) $cond_plu_code;
                if (!empty($products_map[$plu_code])) {
                    array_push($modifier_products, $products_map[$plu_code]);
                } else {
                    throw new MobiException("No Product with plu $plu_code found");
                }
            }

            $modifier = new ModifierModel(intval($modifier['num']), $modifier['name'], $modifier_products);
            array_push($modifiers, $modifier);
        }

        return $modifiers;
    }

    /**
     * Helper to create map of products with plu
     *
     * @param ProductModel[] $products
     *
     * @return ProductModel[]
     */
    private function createProductsMap(array $products): array
    {
        $productsMap = [];

        foreach ($products as $product) {
            $productsMap[$product->getPlu()] = $product;
        }

        return $productsMap;
    }
}
