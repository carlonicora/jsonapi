<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;
use Exception;

class Error implements ExportPreparationInterface
{
    use ExportPreparationTrait;

    /** @var string|null  */
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

    /** @var array */
    private const HTTP_STATUSES = [
        100, 101, 102,
        200, 201, 202, 203, 204, 205, 206, 207, 208, 226,
        300, 301, 302, 303, 304, 305, 307, 308,
        401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 421, 422, 423, 424, 426, 428, 429, 431, 444, 451, 499,
        500, 501, 502, 503, 504, 505, 506, 507, 508, 509, 510, 511, 599
    ];

    public function __construct(
        Exception|Error|null  $e=null,
        ?string $httpStatusCode=null,
        ?string $detail=null,
        ?string $id=null,
        ?string $errorUniqueCode=null,
        ?string $title=null)
    {
        $this->status = null;
        $this->detail = null;

        if ($e !== null){
            if (method_exists($e, 'getHttpStatusCode')) {
                $this->status = $e->getHttpStatusCode();
                $this->code = (string)$e->getCode();
            } elseif (in_array((int)$e->getCode(), self::HTTP_STATUSES, true)) {
                // TODO cleanup usage Exception->code as http status code and remove this elseif. Otherwise 404 code will return 404 http status
                $this->status = (string)$e->getCode();
            } else {
                $this->status = 500;
                $this->code = (string)$e->getCode();
            }

            $this->detail = $e->getMessage();
        }
        if ($httpStatusCode !== null) {
            $this->status = $httpStatusCode;
        }
        if ($detail !== null){
            $this->detail = $detail;
        }
        $this->id = $id;
        $this->title = $title;
        $this->code = $errorUniqueCode;

        $this->meta = new Meta();
        $this->links = new Links();
    }

    /**
     * @return string|null
     */
    public function getDetail(
    ): ?string
    {
        return $this->detail;
    }

    /**
     * @return string|null
     */
    public function getCode(
    ): ?string
    {
        return $this->code;
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

        $this->prepareMeta($this->meta, $response);
        $this->prepareLinks($this->links, $response);

        return $response;
    }
}