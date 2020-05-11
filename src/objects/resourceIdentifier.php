<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\traits\exportPreparationTrait;

class resourceIdentifier implements exportPreparationInterface
{
    use exportPreparationTrait;

    /** @var meta  */
    public meta $meta;

    /** @var string  */
    public string $type;

    /** @var string|null  */
    public ?string $id=null;

    /**
     * resourceIdentifier constructor.
     * @param string $type
     * @param string|null $id
     */
    public function __construct(string $type, ?string $id=null)
    {
        $this->type = $type;
        $this->id = $id;

        $this->meta = new meta();
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        $response = [
            'type' => $this->type
        ];

        if ($this->id !== null) {
            $response['id'] = $this->id;
        }

        $this->prepareMeta($this->meta, $response);

        return $response;
    }
}