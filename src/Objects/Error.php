<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;
use Throwable;

class Error implements ExportPreparationInterface
{
    use ExportPreparationTrait;

    /** @var string  */
    public ?string $status;

    /** @var string|null  */
    private ?string $id;

    /** @var string|null  */
    private ?string $code;

    /** @var string|null  */
    private ?string $title;

    /** @var string|null  */
    private ?string $detail;

    /** @var array|null  */
    //private ?array $source=null;

    /** @var Meta  */
    public Meta $meta;

    /** @var Links */
    public Links $links;

    public function __construct(
        ?Throwable $e=null,
        ?string $status=null,
        ?string $detail=null,
        ?string $id=null,
        ?string $code=null,
        ?string $title=null)
    {
        $this->status = null;
        $this->detail = null;

        if ($e !== null){
            $this->status = (string)$e->getCode();
            $this->detail = $e->getMessage();
        }
        if ($status !== null) {
            $this->status = $status;
        }
        if ($detail !== null){
            $this->detail = $detail;
        }
        $this->id = $id;
        $this->title = $title;
        $this->code = $code;

        $this->meta = new Meta();
        $this->links = new Links();
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        $response = [];

        if ($this->status !== null) {
            $response['status'] = $this->status;
        }

        if ($this->id !== null) {
            $response['id'] = $this->id;
        }

        if ($this->code !== null) {
            $response['code'] = $this->code;
        }

        if ($this->title !== null) {
            $response['title'] = $this->title;
        }

        if ($this->detail !== null) {
            $response['detail'] = $this->detail;
        }

        /*
        if ($this->source !== null) {
            $response['source'] = $this->source;
        }
        */

        $this->prepareMeta($this->meta, $response);
        $this->prepareLinks($this->links, $response);

        return $response;
    }

}