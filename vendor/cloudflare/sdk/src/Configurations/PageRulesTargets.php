<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 19/09/2017
 * Time: 18:37
 */

namespace Cloudflare\API\Configurations;

class PageRulesTargets implements Configurations
{
    private $targets;

    public function __construct(string $queryUrl)
    {
        $this->targets = [
            [
                'target' => 'url',
                'constraint' => [
                    'operator' => 'matches',
                    'value' => $queryUrl
                ]
            ]
        ];
    }

    public function getArray(): array
    {
        return $this->targets;
    }
}
