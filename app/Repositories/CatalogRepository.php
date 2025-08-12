<?php

namespace App\Repositories;

class CatalogRepository
{

    public function getList(
        int $page = 1,
        int $perPage = 9,
        ?string $sortBy = null,
        string $direction = 'asc',
        array $filter = []
    ) {
        $data = require(useBaseDir('fixtures/products.php'));

        return useArrayNavHelper($data)
            ->sortBy($sortBy, $direction)
            ->paginate($page, $perPage)
            ->get();
    }
}