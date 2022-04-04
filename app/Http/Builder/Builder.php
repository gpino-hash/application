<?php

namespace App\Http\Builder;

use JetBrains\PhpStorm\Pure;

abstract class Builder
{
    #[Pure]
    abstract public static function builder();

    #[Pure]
    abstract public function build();
}
