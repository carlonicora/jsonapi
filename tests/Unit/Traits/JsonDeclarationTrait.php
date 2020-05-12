<?php
namespace CarloNicora\JsonApi\tests\Unit\Traits;

trait JsonDeclarationTrait
{
    /** @var string  */
    protected string $jsonDocumentMinimal = '{"meta":[]}';

    protected string $jsonResponseError = '{"errors":[{"status":"500","title":"Internal Server Error","detail":"Failure in converting data to JSON"}]}';
}