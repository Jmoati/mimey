<?php

declare(strict_types=1);

namespace Mimey;

final class MimeMappingGenerator
{
    public function __construct(
        private string $mime_types_text
    ) {
    }

    public function generateMapping(): array
    {
        $mapping = [];

        $lines = explode("\n", $this->mime_types_text);
        foreach ($lines as $line) {
            $line = trim((string) preg_replace('~\\#.*~', '', $line));
            $parts = $line ? array_values(array_filter(explode("\t", $line))) : [];
            if (2 === count($parts)) {
                $mime = trim($parts[0]);
                $extensions = explode(' ', $parts[1]);
                foreach ($extensions as $extension) {
                    $extension = trim($extension);
                    if ($mime && $extension) {
                        $mapping['mimes'][$extension][] = $mime;
                        $mapping['extensions'][$mime][] = $extension;
                        $mapping['mimes'][$extension] = array_unique($mapping['mimes'][$extension]);
                        $mapping['extensions'][$mime] = array_unique($mapping['extensions'][$mime]);
                    }
                }
            }
        }

        return $mapping;
    }

    public function generateMappingCode(): string
    {
        $mapping = $this->generateMapping();
        $mapping_export = var_export($mapping, true);

        return "<?php return $mapping_export;";
    }
}
