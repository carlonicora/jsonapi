<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;

class Link implements ExportPreparationInterface
{
    /** @var string  */
    public string $name;

    /** @var string  */
    public string $href;

    /** @var Meta|null  */
    public ?Meta $meta=null;

    /**
     * Link constructor.
     * @param string $name
     * @param string $href
     * @param Meta|null $meta
     */
    public function __construct(string $name, string $href, ?Meta $meta=null)
    {
        $this->name = $name;
        $this->href = $href;

        if ($meta !== null) {
            $this->meta = $meta;
        }
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        $response = [];

        if (null === $this->meta) {
            $response[$this->name] = $this->href;
        } else {
            $response[$this->name] = [];
            $response[$this->name]['href'] = $this->href;
            $response[$this->name]['meta'] = $this->meta->prepare();
        }

        return $response;
    }


}