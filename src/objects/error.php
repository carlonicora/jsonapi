<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\traits\exportPreparationTrait;

class error implements exportPreparationInterface
{
    use exportPreparationTrait;

    /** @var string  */
    private ?string $status;

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


    /** @var meta  */
    public meta $meta;

    /** @var links */
    public links $links;

    public function __construct(
        ?string $status=null,
        ?string $detail=null,
        ?string $id=null,
        ?string $code=null,
        ?string $title=null)
    {
        $this->status = $status;
        $this->id = $id;
        $this->code = $code;
        $this->title = $title;
        $this->detail = $detail;

        $this->meta = new meta();
        $this->links = new links();
    }

    /**
     * @return array
     */
    public function prepare(): array
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