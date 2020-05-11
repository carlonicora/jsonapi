<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;

class link implements exportPreparationInterface
{
    /** @var string  */
    public string $name;

    /** @var string  */
    public string $href;

    /** @var meta|null  */
    public ?meta $meta=null;

    /**
     * link constructor.
     * @param string $name
     * @param string $href
     * @param meta|null $meta
     */
    public function __construct(string $name, string $href, ?meta $meta=null)
    {
        $this->name = $name;
        $this->href = $href;

        if ($meta !== null) {
            $this->meta = $meta;
        }
    }

    /**
     * @return array
     */
    public function prepare(): array
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