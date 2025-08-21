<?php

namespace fmfrlx\Filamentsecretfield\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \fmfrlx\FilamentsecretfieldModule\FilamentsecretfieldModule
 */
class FilamentsecretfieldModule extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \fmfrlx\FilamentsecretfieldModule\FilamentsecretfieldModule::class;
    }
}
