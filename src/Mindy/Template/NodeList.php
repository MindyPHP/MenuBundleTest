<?php

namespace Mindy\Template;

/**
 * Class NodeList.
 */
class NodeList extends Node
{
    /**
     * @var Node[]
     */
    protected $nodes;

    public function __construct($nodes, $line)
    {
        parent::__construct($line);
        $this->nodes = $nodes;
    }

    public function compile(Compiler $compiler, $indent = 0)
    {
        foreach ($this->nodes as $node) {
            $node->compile($compiler, $indent);
        }
    }
}
