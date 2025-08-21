<?php

namespace fmfrlx\Filamentsecretfield\Forms\Components;

use Filament\Forms\Components\Field;

class MaskedInput extends Field
{
    protected string $view = 'filamentsecretfield::components.masked-input';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(true);
    }
}
