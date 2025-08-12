<?php
namespace Bladex;

class ArrayNavHelper
{
    private array $data;
    private string $rootkey;
    private string $metaKey;

    public function __construct(array $data, $rootkey = 'items', $metaKey = 'meta')
    {
        $this->data = $data;
        $this->rootkey = $rootkey;
        $this->metaKey = $metaKey;
    }

    public function sortBy(?string $key, string $direction = 'asc'): self
    {
        if ($key !== null) {
            usort($this->data, function ($a, $b) use ($key, $direction) {
                $result = $a[$key] <=> $b[$key];
                return $direction === 'desc' ? -$result : $result;
            });
        }
        return $this;
    }

    public function paginate(int $page = 1, int $perPage = 10): self
    {
        $total = count($this->data);
        $offset = ($page - 1) * $perPage;
        $paged = array_slice($this->data, $offset, $perPage);

        $this->data = [
            $this->rootkey => $paged,
            $this->metaKey => [
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => ceil($total / $perPage),
            ]
        ];

        return $this;
    }

    public function get(): array
    {
        return $this->data;
    }
}
