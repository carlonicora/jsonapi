<?php
namespace CarloNicora\JsonApi\tests\UnitS\Traits;

trait JsonDeclarationTrait
{
    /** @var string  */
    protected string $jsonDocumentMinimal = '{"Meta":[]}';

    protected string $jsonResponseError = '{"errors":[{"status":"500","title":"Internal Server Error","detail":"Failure in converting data to JSON"}]}';
}