<?php

namespace App\Repositories;

class CatalogRepository
{

    public function getProducts(
        int $offset = 1,
        int $limit = 12,
        ?string $sortBy = null,
        string $direction = 'asc',
        array $filter = []
    ) {
        $products = useFixture('products');

        return array_slice($products, $offset, $limit);
    }
}