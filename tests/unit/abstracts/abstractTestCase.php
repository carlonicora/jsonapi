<?php
namespace carlonicora\jsonapi\tests\unit\abstracts;

use carlonicora\jsonapi\document;
use carlonicora\jsonapi\objects\attributes;
use carlonicora\jsonapi\objects\error;
use carlonicora\jsonapi\objects\link;
use carlonicora\jsonapi\objects\links;
use carlonicora\jsonapi\objects\meta;
use carlonicora\jsonapi\objects\relationship;
use carlonicora\jsonapi\objects\resourceLinkage;
use carlonicora\jsonapi\objects\resourceObject;
use carlonicora\jsonapi\objects\resourceIdentifier;
use carlonicora\jsonapi\response;
use carlonicora\jsonapi\tests\unit\traits\arrayDeclarationTrait;
use carlonicora\jsonapi\tests\unit\traits\jsonDeclarationTrait;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class abstractTestCase extends TestCase
{
    use arrayDeclarationTrait;
    use jsonDeclarationTrait;

    protected function generateResourceIdentifier() : resourceIdentifier
    {
        return new resourceIdentifier('user', '1');
    }

    protected function generateResourceObject() : resourceObject
    {
        return new resourceObject('user', '1');
    }

    protected function generateRelationship() : relationship
    {
        return new relationship();
    }

    /**
     * @return resourceObject
     * @throws Exception
     */
    protected function generateResourceObjectWithAttributes() : resourceObject
    {
        $response = new resourceObject('user', '1');
        $response->attributes = $this->generateAttributes();
        return $response;
    }

    /**
     * @return resourceObject
     * @throws Exception
     */
    protected function generateCompleteResourceObject() : resourceObject
    {
        $response = $this->generateResourceObjectWithAttributes();
        $response->links->add($this->generateLink());
        $response->meta->add('metaOne',1);
        $response->meta->add('metaTwo',2);

        return $response;
    }

    /**
     * @return resourceObject
     * @throws Exception
     */
    protected function generateSecondaryResourceObject() : resourceObject
    {
        $response = new resourceObject('image', '10');
        $response->attributes->add('url', 'https://image/10.jpg');
        $response->links->add(new link('self', 'https://image/10'));

        return $response;
    }

    /**
     * @return attributes
     * @throws Exception
     */
    protected function generateAttributes() : attributes
    {
        $response = new attributes();
        $response->add('name', 'Carlo');

        return $response;
    }

    protected function generateEmptyAttributes() : attributes
    {
        return new attributes();
    }

    protected function generateResponse(?string $status=null) : response
    {
        $response = new response();

        if ($status !== null) {
            $response->httpStatus = $status;
        }

        return $response;
    }

    /**
     * @return document
     */
    protected function generateDocumentEmpty() : document
    {
        return new document();
    }

    /**
     * @return meta
     */
    protected function generateEmptyMeta() : meta
    {
        return new meta();
    }

    /**
     * @return meta
     * @throws Exception
     */
    protected function generateMeta() : meta
    {
        $meta = $this->generateEmptyMeta();
        $meta->add('metaOne', 1);
        $meta->add('metaTwo', 2);
        return $meta;
    }

    protected function generateLink() : link
    {
        return new link('self', 'https://self');
    }

    protected function generateRelatedLink() : link
    {
        return new link('related', 'https://related');
    }

    /**
     * @return link
     * @throws Exception
     */
    protected function generateLinkWithMeta() : link
    {
        return new link('self', 'https://self', $this->generateMeta());
    }

    protected function generateEmptyLinks() : links
    {
        return new links();
    }

    protected function generateMinimalError() : error
    {
        return new error();
    }

    protected function generateError() : error
    {
        return new error('status', 'detail', 'id', 'code', 'title');
    }

    /**
     * @return resourceLinkage
     */
    protected function generateResourceLinkage() : resourceLinkage
    {
        return new resourceLinkage();
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