<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link http://phpdoc.org
 */

namespace phpDocumentor\Descriptor\Builder\Reflector\Tags;

use InvalidArgumentException;
use phpDocumentor\Descriptor\Builder\Reflector\AssemblerAbstract;
use phpDocumentor\Descriptor\Tag\ExampleDescriptor;
use phpDocumentor\Reflection\DocBlock\ExampleFinder;
use phpDocumentor\Reflection\DocBlock\Tags\Example;
use Webmozart\Assert\Assert;

/**
 * This class collects data from the example tag definition of the Reflection library, tries to find the correlating
 * example file on disk and creates a complete Descriptor from that.
 */
class ExampleAssembler extends AssemblerAbstract
{
    /** @var ExampleFinder */
    private $finder;

    /**
     * Initializes this assembler with the means to find the example file on disk.
     */
    public function __construct(ExampleFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Creates a new Descriptor from the given Reflector.
     *
     * @param Example $data
     *
     * @throws InvalidArgumentException If the provided parameter is not of type ExampleTag; the interface won't let
     *   up typehint the signature.
     */
    public function create(object $data) : ExampleDescriptor
    {
        Assert::isInstanceOf($data, Example::class);
        $descriptor = new ExampleDescriptor($data->getName());
        $descriptor->setFilePath($data->getFilePath());
        $descriptor->setStartingLine($data->getStartingLine());
        $descriptor->setLineCount($data->getLineCount());
        $descriptor->setDescription((string) $data->getDescription());
        $descriptor->setExample($this->finder->find($data));

        return $descriptor;
    }
}
