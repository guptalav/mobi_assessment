<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class POSTest extends TestCase
{
    public function testValidProducts(): void
    {
        $pos_connection_mock = \Mockery::mock(\Mobi\Assessment\Classes\Connection\POSXMLConnect::class);
        $xml_source_data = simplexml_load_file("tests/mocks/valid_payload.xml");
        $pos_connection_mock->shouldReceive('getData')->andReturn($xml_source_data);

        $pos = new \Mobi\Assessment\Classes\POS($pos_connection_mock);
        $products = $pos->getProducts();
        $xml_source_data_products = $xml_source_data->plu_list->plu;

        $this->assertCount(30, $products);
        foreach ($products as $key=>$product) {
            $this->assertEquals($xml_source_data_products[$key]['code'], $product->getPlu());
            $this->assertEquals($xml_source_data_products[$key]['name'], $product->getName());
            $this->assertEquals($xml_source_data_products[$key]['name2'], $product->getBackendName());

            $source_prices = [];
            foreach ($xml_source_data_products[$key]->price as $price) {
                if (intval($price) > 0) {
                    array_push($source_prices, floatval(intval($price)/100));
                }
            }

            $this->assertEquals($source_prices, $product->getPrices());
        }
    }

    public function testInvalidDataGetProducts() {
        $pos_connection_mock = \Mockery::mock(\Mobi\Assessment\Classes\Connection\POSXMLConnect::class);
        $xml_source_data = simplexml_load_file("tests/mocks/invalid_payload.xml");
        $pos_connection_mock->shouldReceive('getData')->andReturn($xml_source_data);

        $pos = new \Mobi\Assessment\Classes\POS($pos_connection_mock);

        $this->expectException(\Mobi\Assessment\Exceptions\MobiException::class);
        $pos->getProducts();
    }

    public function testValidCategories(): void
    {
        $pos_connection_mock = \Mockery::mock(\Mobi\Assessment\Classes\Connection\POSXMLConnect::class);
        $xml_source_data = simplexml_load_file("tests/mocks/valid_payload.xml");
        $pos_connection_mock->shouldReceive('getData')->andReturn($xml_source_data);

        $pos = new \Mobi\Assessment\Classes\POS($pos_connection_mock);
        $categories = $pos->getCategories();
        $xml_source_data_categories = $xml_source_data->category_group_list->category_group;

        $this->assertCount(2, $categories);
        foreach ($categories as $key=>$category) {
            $this->assertEquals($xml_source_data_categories[$key]['name'], $category->getName());
            $this->assertEquals(intval($xml_source_data_categories[$key]['num']), $category->getId());

            foreach ($category->getProducts() as $key_product=>$product) {
                $this->assertInstanceOf(\Mobi\Assessment\Models\ProductModel::class, $category->getProducts()[$key_product]);
                $this->assertEquals((string) $xml_source_data_categories[$key]->cond_plu_code[$key_product], $product->getPlu());
            }
        }
    }

    public function testInvalidDataGetCategories() {
        $pos_connection_mock = \Mockery::mock(\Mobi\Assessment\Classes\Connection\POSXMLConnect::class);
        $xml_source_data = simplexml_load_file("tests/mocks/invalid_payload.xml");
        $pos_connection_mock->shouldReceive('getData')->andReturn($xml_source_data);

        $pos = new \Mobi\Assessment\Classes\POS($pos_connection_mock);

        $this->expectException(\Mobi\Assessment\Exceptions\MobiException::class);
        $pos->getCategories();
    }

    public function testValidModifiers(): void
    {
        $pos_connection_mock = \Mockery::mock(\Mobi\Assessment\Classes\Connection\POSXMLConnect::class);
        $xml_source_data = simplexml_load_file("tests/mocks/valid_payload.xml");
        $pos_connection_mock->shouldReceive('getData')->andReturn($xml_source_data);

        $pos = new \Mobi\Assessment\Classes\POS($pos_connection_mock);
        $modifiers = $pos->getModifiers();
        $xml_source_data_modifiers = $xml_source_data->modifier_group_list->modifier_group;

        $this->assertCount(4, $modifiers);
        foreach ($modifiers as $key=>$modifier) {
            $this->assertEquals($xml_source_data_modifiers[$key]['name'], $modifier->getName());
            $this->assertEquals(intval($xml_source_data_modifiers[$key]['num']), $modifier->getId());

            foreach ($modifier->getProducts() as $key_product=>$product) {
                $this->assertInstanceOf(\Mobi\Assessment\Models\ProductModel::class, $modifier->getProducts()[$key_product]);
                $this->assertEquals((string) $xml_source_data_modifiers[$key]->cond_plu_code[$key_product], $product->getPlu());
            }
        }
    }

    public function testInvalidDataGetModifiers() {
        $pos_connection_mock = \Mockery::mock(\Mobi\Assessment\Classes\Connection\POSXMLConnect::class);
        $xml_source_data = simplexml_load_file("tests/mocks/invalid_payload.xml");
        $pos_connection_mock->shouldReceive('getData')->andReturn($xml_source_data);

        $pos = new \Mobi\Assessment\Classes\POS($pos_connection_mock);

        $this->expectException(\Mobi\Assessment\Exceptions\MobiException::class);
        $pos->getModifiers();
    }
}