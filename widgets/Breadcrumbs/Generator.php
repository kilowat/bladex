<?php

namespace Widgets\Breadcrumbs;


class Generator
{
    protected array $trail = [];

    public function push(string $title, ?string $url = null): static
    {
        $this->trail[] = ['title' => $title, 'url' => $url];
        return $this;
    }

    public function get(): array
    {
        return $this->trail;
    }
}