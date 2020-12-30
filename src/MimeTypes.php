<?php

namespace Mimey;

final class MimeTypes implements MimeTypesInterface
{
	private static ?array $built_in = null;
	
	public function __construct(
	    private ?array $mapping = null
    ) {
		if (null === $mapping) {
            $this->mapping = self::getBuiltIn();
        }
	}

	public function getMimeType(string $extension): ?string
	{
        assert(is_array($this->mapping));

		$extension = $this->cleanInput($extension);
		
		if ($this->mapping['mimes'][$extension] ?? []) {
			return $this->mapping['mimes'][$extension][0];
		}

		return null;
	}

	public function getExtension(string $mime_type): ?string
	{
        assert(is_array($this->mapping));
        
        $mime_type = $this->cleanInput($mime_type);
		
		if ($this->mapping['extensions'][$mime_type] ?? []) {
			return $this->mapping['extensions'][$mime_type][0];
		}
		return null;
	}

	public function getAllMimeTypes(string $extension): array
	{
        assert(is_array($this->mapping));
        
        $extension = $this->cleanInput($extension);
	
		return $this->mapping['mimes'][$extension] ?? [];
	}

	public function getAllExtensions(string $mime_type): array
	{
        assert(is_array($this->mapping));
        
        $mime_type = $this->cleanInput($mime_type);
		
		return $this->mapping['extensions'][$mime_type] ?? [];
	}

	private static function getBuiltIn(): array
	{
		if (null === self::$built_in) {
			self::$built_in = require(dirname(__DIR__) . '/mime.types.php');
		}

		return self::$built_in;
	}

	private function cleanInput(string $input): string
	{
		return strtolower(trim($input));
	}
}
