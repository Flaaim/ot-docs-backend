<?php

declare(strict_types=1);

use App\Shared\Domain\Service\Template\TemplatePath;

return [
    TemplatePath::class => function () {
        return new TemplatePath(sys_get_temp_dir());
    },
];