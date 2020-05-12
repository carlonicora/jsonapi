<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Interfaces\ImportInterface;
use Exception;
use RuntimeException;

class Links implements ExportPreparationInterface, ImportInterface
{
    /** @var Link[]  */
    private array $links=[];

    /**
     * @param Link $link
     * @throws Exception
     */
    public function add(Link $link) : void
    {
        if (array_key_exists($link->name, $this->links)){
            throw new RuntimeException('Link key already exising', 1);
        }

        $this->links[$link->name] = $link;
    }

    /**
     * @param string $name
     * @return Link
     * @throws Exception
     */
    public function get(string $name): Link {
        if (!array_key_exists($name, $this->links)){
            throw new RuntimeException('Link key not exising', 2);

        }

        return $this->links[$name];
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->links);
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        $response = [];

        foreach ($this->links as $link){
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $response = array_merge($response, $link->prepare());
        }

        return $response;
    }

    /**
     * @param array $data
     * @param array|null $included
     * @throws Exception
     */
    public function importArray(array $data, array $included=null): void
    {
        foreach ($data as $linkKey=>$linkValue){
            if (is_array($linkValue)) {
                $meta = new Meta();
                $meta->importArray($linkValue['Meta']);
                $link = new Link($linkKey, $linkValue['href'], $meta);
            } else {
                $link = new Link($linkKey, $linkValue);
            }

            $this->add($link);
        }
    }
}