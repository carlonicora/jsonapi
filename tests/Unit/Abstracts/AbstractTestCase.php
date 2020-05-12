<?php
namespace CarloNicora\JsonApi\tests\Unit\Abstracts;

use CarloNicora\JsonApi\Document;
use CarloNicora\JsonApi\Objects\Attributes;
use CarloNicora\JsonApi\Objects\Error;
use CarloNicora\JsonApi\Objects\Link;
use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\Objects\Meta;
use CarloNicora\JsonApi\Objects\Relationship;
use CarloNicora\JsonApi\Objects\ResourceLinkage;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\Objects\ResourceIdentifier;
use CarloNicora\JsonApi\Response;
use CarloNicora\JsonApi\tests\Unit\Traits\ArrayDeclarationTrait;
use CarloNicora\JsonApi\tests\Unit\Traits\JsonDeclarationTrait;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class AbstractTestCase extends TestCase
{
    use arrayDeclarationTrait;
    use jsonDeclarationTrait;

    protected function generateResourceIdentifier() : ResourceIdentifier
    {
        return new ResourceIdentifier('user', '1');
    }

    protected function generateResourceObject() : ResourceObject
    {
        return new ResourceObject('user', '1');
    }

    protected function generateRelationship() : Relationship
    {
        return new Relationship();
    }

    /**
     * @return ResourceObject
     * @throws Exception
     */
    protected function generateResourceObjectWithAttributes() : ResourceObject
    {
        $response = new ResourceObject('user', '1');
        $response->attributes = $this->generateAttributes();
        return $response;
    }

    /**
     * @return ResourceObject
     * @throws Exception
     */
    protected function generateCompleteResourceObject() : ResourceObject
    {
        $response = $this->generateResourceObjectWithAttributes();
        $response->links->add($this->generateLink());
        $response->meta->add('metaOne',1);
        $response->meta->add('metaTwo',2);

        return $response;
    }

    /**
     * @return ResourceObject
     * @throws Exception
     */
    protected function generateSecondaryResourceObject() : ResourceObject
    {
        $response = new ResourceObject('image', '10');
        $response->attributes->add('url', 'https://image/10.jpg');
        $response->links->add(new Link('self', 'https://image/10'));

        return $response;
    }

    /**
     * @return Attributes
     * @throws Exception
     */
    protected function generateAttributes() : Attributes
    {
        $response = new Attributes();
        $response->add('name', 'Carlo');

        return $response;
    }

    protected function generateEmptyAttributes() : Attributes
    {
        return new Attributes();
    }

    protected function generateResponse(?string $status=null) : Response
    {
        $response = new Response();

        if ($status !== null) {
            $response->httpStatus = $status;
        }

        return $response;
    }

    /**
     * @return Document
     */
    protected function generateDocumentEmpty() : Document
    {
        return new Document();
    }

    /**
     * @return Meta
     */
    protected function generateEmptyMeta() : Meta
    {
        return new Meta();
    }

    /**
     * @return Meta
     * @throws Exception
     */
    protected function generateMeta() : Meta
    {
        $meta = $this->generateEmptyMeta();
        $meta->add('metaOne', 1);
        $meta->add('metaTwo', 2);
        return $meta;
    }

    protected function generateLink() : Link
    {
        return new Link('self', 'https://self');
    }

    protected function generateRelatedLink() : Link
    {
        return new Link('related', 'https://related');
    }

    /**
     * @return Link
     * @throws Exception
     */
    protected function generateLinkWithMeta() : Link
    {
        return new Link('self', 'https://self', $this->generateMeta());
    }

    protected function generateEmptyLinks() : Links
    {
        return new Links();
    }

    protected function generateMinimalError() : Error
    {
        return new Error();
    }

    protected function generateError() : Error
    {
        return new Error('status', 'detail', 'id', 'code', 'title');
    }

    /**
     * @return ResourceLinkage
     */
    protected function generateResourceLinkage() : ResourceLinkage
    {
        return new ResourceLinkage();
    }

    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        try {
            $reflection = new ReflectionClass(get_class($object));
            $method = $reflection->getMethod($methodName);
            $method->setAccessible(true);
            return $method->invokeArgs($object, $parameters);
        } catch (ReflectionException $e) {
            return null;
        }
    }
}