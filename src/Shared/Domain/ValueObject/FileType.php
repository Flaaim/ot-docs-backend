<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Service\Template\TemplatePath;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class FileType extends StringType
{
    private static TemplatePath $path;
    public static function appendPath(TemplatePath $path): void
    {
        self::$path = $path;
    }
    public const NAME = 'file';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof File ? $value->getPath() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?File
    {
        return !empty($value) ? new File((string)$value, self::$path) : null;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 255;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
