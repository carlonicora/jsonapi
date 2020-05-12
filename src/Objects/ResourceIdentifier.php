<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;

class ResourceIdentifier implements ExportPreparationInterface
{
    use ExportPreparationTrait;

    /** @var Meta  */
    public Meta $resourceIdentifierMeta;

    /** @var string  */
    public string $type;

    /** @var string|null  */
    public ?string $id=null;

    /**
     * ResourceIdentifier constructor.
     * @param string $type
     * @param string|null $id
     */
    public function __construct(string $type, ?string $id=null)
    {
        $this->type = $type;
        $this->id = $id;

        $this->resourceIdentifierMeta = new Meta();
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        $response = [
            'type' => $this->type
        ];

        if ($this->id !== null) {
            $response['id'] = $this->id;
        }

        $this->prepareMeta($this->resourceIdentifierMeta, $response);

        return $response;
    }
}