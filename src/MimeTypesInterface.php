<?php

declare(strict_types=1);

namespace Mimey;

interface MimeTypesInterface
{
    public function getMimeType(string $extension): ?string;

    public function getExtension(string $mime_type): ?string;

    public function getAllMimeTypes(string $extension): array;

    public function getAllExtensions(string $mime_type): array;
}
