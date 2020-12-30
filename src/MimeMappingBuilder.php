<?php

declare(strict_types=1);

namespace Mimey;

final class MimeMappingBuilder
{
    private function __construct(
        private array $mapping
    ) {
    }

    public function add(string $mime, string $extension, bool $prepend_extension = true, bool $prepend_mime = true): void
    {
        $existing_extensions = $this->mapping['extensions'][$mime] ?? [];
        $existing_mimes = $this->mapping['mimes'][$extension] ?? [];

        if ($prepend_extension) {
            array_unshift($existing_extensions, $extension);
        } else {
            $existing_extensions[] = $extension;
        }
        if ($prepend_mime) {
            array_unshift($existing_mimes, $mime);
        } else {
            $existing_mimes[] = $mime;
        }

        $this->mapping['extensions'][$mime] = array_unique($existing_extensions);
        $this->mapping['mimes'][$extension] = array_unique($existing_mimes);
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function compile(): string
    {
        $mapping = $this->getMapping();
        $mapping_export = var_export($mapping, true);

        return "<?php return $mapping_export;";
    }

    /**
     * @param resource $context context for `file_put_contents`
     */
    public function save(string $file, int $flags = 0, $context = null): int | bool
    {
        return file_put_contents($file, $this->compile(), $flags, $context);
    }

    public static function create(): self
    {
        return self::load(dirname(__DIR__).'/mime.types.php');
    }

    public static function load(string $file): self
    {
        return new self(require $file);
    }

    public static function blank(): self
    {
        return new self(['mimes' => [], 'extensions' => []]);
    }
}
