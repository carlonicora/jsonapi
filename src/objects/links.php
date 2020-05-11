<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\interfaces\importInterface;
use Exception;
use RuntimeException;

class links implements exportPreparationInterface, importInterface
{
    /** @var link[]  */
    private array $links=[];

    /**
     * @param link $link
     * @throws Exception
     */
    public function add(link $link) : void
    {
        if (array_key_exists($link->name, $this->links)){
            throw new RuntimeException('link key already exising', 1);
        }

        $this->links[$link->name] = $link;
    }

    /**
     * @param string $name
     * @return link
     * @throws Exception
     */
    public function get(string $name): link {
        if (!array_key_exists($name, $this->links)){
            throw new RuntimeException('link key not exising', 2);

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
                $meta = new meta();
                $meta->importArray($linkValue['meta']);
                $link = new link($linkKey, $linkValue['href'], $meta);
            } else {
                $link = new link($linkKey, $linkValue);
            }

            $this->add($link);
        }
    }
}