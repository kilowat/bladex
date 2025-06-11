<?php

namespace Bladex;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Inject
{
    public function __construct(
        public ?string $name = null,
        public ?string $alias = null
    ) {
    }
}