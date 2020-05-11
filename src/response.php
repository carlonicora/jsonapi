<?php
namespace carlonicora\jsonapi;

use carlonicora\jsonapi\objects\error;
use JsonException;

class response
{
    /** @var document  */
    public document $document;

    /** @var string  */
    public string $httpStatus=self::HTTP_STATUS_200;

    /** @var string  */
    public const HTTP_STATUS_200='200';

    /** @var string  */
    public const HTTP_STATUS_201='201';

    /** @var string  */
    public const HTTP_STATUS_204='204';

    /** @var string  */
    public const HTTP_STATUS_205='205';

    /** @var string  */
    public const HTTP_STATUS_304='304';

    /** @var string  */
    public const HTTP_STATUS_400='400';

    /** @var string  */
    public const HTTP_STATUS_401='401';

    /** @var string  */
    public const HTTP_STATUS_403='403';

    /** @var string  */
    public const HTTP_STATUS_404='404';

    /** @var string  */
    public const HTTP_STATUS_405='405';

    /** @var string  */
    public const HTTP_STATUS_406='406';

    /** @var string  */
    public const HTTP_STATUS_409='409';

    /** @var string  */
    public const HTTP_STATUS_410='410';

    /** @var string  */
    public const HTTP_STATUS_411='411';

    /** @var string  */
    public const HTTP_STATUS_412='412';

    /** @var string  */
    public const HTTP_STATUS_415='415';

    /** @var string  */
    public const HTTP_STATUS_422='422';

    /** @var string  */
    public const HTTP_STATUS_428='428';

    /** @var string  */
    public const HTTP_STATUS_429='429';

    /** @var string  */
    public const HTTP_STATUS_500='500';

    /** @var string  */
    public const HTTP_STATUS_501='501';

    /** @var string  */
    public const HTTP_STATUS_502='502';

    /** @var string  */
    public const HTTP_STATUS_503='503';

    /** @var string  */
    public const HTTP_STATUS_504='504';

    /**
     * response constructor.
     */
    public function __construct()
    {
        $this->document = new document();
    }

    /**
     * @return string|null
     */
    public function render() : ?string {
        try {
            $response = $this->generateResponseData();
        } catch (JsonException $e) {
            $response = null;
        }

        $this->renderHeaders();

        return $response;
    }

    /**
     *
     */
    public function renderHeaders() : void
    {
        $this->renderProtocolHeader();
        header('Content-Type: application/vnd.api+json');
    }

    /**
     *
     */
    public function renderProtocolHeader() : void
    {
        $GLOBALS['http_response_code'] = $this->httpStatus;
        header($this->generateProtocol() . ' ' . $this->httpStatus . ' ' . $this->generateStatusText());
    }

    /**
     * @return string|null
     * @throws JsonException
     */
    protected function generateResponseData() : ?string
    {
        if (
            $this->httpStatus === self::HTTP_STATUS_201
            ||
            $this->httpStatus === self::HTTP_STATUS_204
            ||
            $this->httpStatus === self::HTTP_STATUS_304
        )
        {
            return null;
        }

        try {
            return $this->document->export();
        } catch (JsonException $e) {
            $this->httpStatus = self::HTTP_STATUS_500;
            $this->document = new document();
            $this->document->addError( new error(self::HTTP_STATUS_500, 'Failure in converting data to JSON', null, null, $this->generateStatusText()));

            return $this->document->export();
        }
    }

    /**
     * @return string
     */
    private function generateProtocol() : string
    {
        return ($_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1');
    }

    /**
     * @return string
     */
    private function generateStatusText() : string
    {
        switch ($this->httpStatus) {
            case self::HTTP_STATUS_201:
                return 'Created';
                break;
            case self::HTTP_STATUS_204:
                return 'No Content';
                break;
            case self::HTTP_STATUS_304:
                return 'Not Modified';
                break;
            case self::HTTP_STATUS_400:
                return 'Bad Request';
                break;
            case self::HTTP_STATUS_401:
                return 'Unauthorized';
                break;
            case self::HTTP_STATUS_403:
                return 'Forbidden';
                break;
            case self::HTTP_STATUS_404:
                return 'Not Found';
                break;
            case self::HTTP_STATUS_405:
                return 'Method Not Allowed';
                break;
            case self::HTTP_STATUS_406:
                return 'Not Acceptable';
                break;
            case self::HTTP_STATUS_409:
                return 'Conflict';
                break;
            case self::HTTP_STATUS_410:
                return 'Gone';
                break;
            case self::HTTP_STATUS_411:
                return 'Length Required';
                break;
            case self::HTTP_STATUS_412:
                return 'Precondition Failed';
                break;
            case self::HTTP_STATUS_415:
                return 'Unsupported Media Type';
                break;
            case self::HTTP_STATUS_422:
                return 'Unprocessable Entity';
                break;
            case self::HTTP_STATUS_428:
                return 'Precondition Required';
                break;
            case self::HTTP_STATUS_429:
                return 'Too Many Requests';
                break;
            case self::HTTP_STATUS_500:
                return 'Internal Server Error';
                break;
            case self::HTTP_STATUS_501:
                return 'Not Implemented';
                break;
            case self::HTTP_STATUS_502:
                return 'Bad Gateway';
                break;
            case self::HTTP_STATUS_503:
                return 'Service Unavailable';
                break;
            case self::HTTP_STATUS_504:
                return 'Gateway Timeout';
                break;
            case self::HTTP_STATUS_200:
            default:
                return 'OK';
                break;
        }
    }
}