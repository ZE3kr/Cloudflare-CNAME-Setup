<?php
/**
 * Created by PhpStorm.
 * User: junade
 * Date: 09/06/2017
 * Time: 16:17
 */

namespace Cloudflare\API\Endpoints;

use Cloudflare\API\Adapter\Adapter;
use Cloudflare\API\Configurations\PageRulesActions;
use Cloudflare\API\Configurations\PageRulesTargets;

class PageRules implements API
{
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @param string $zoneID
     * @param PageRulesTargets $target
     * @param PageRulesActions $actions
     * @param bool $active
     * @param int|null $priority
     * @return bool
     */
    public function createPageRule(
        string $zoneID,
        PageRulesTargets $target,
        PageRulesActions $actions,
        bool $active = true,
        int $priority = null
    ): bool {
        $options = [
            'targets' => $target->getArray(),
            'actions' => $actions->getArray()
        ];

        if ($active !== null) {
            $options['status'] = $active == true ? 'active' : 'disabled';
        }

        if ($priority !== null) {
            $options['priority'] = $priority;
        }


        $query = $this->adapter->post('zones/' . $zoneID . '/pagerules', [], $options);

        $body = json_decode($query->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function listPageRules(
        string $zoneID,
        string $status = null,
        string $order = null,
        string $direction = null,
        string $match = null
    ): array {
        if ($status === null && !in_array($status, ['active', 'disabled'])) {
            throw new EndpointException('Page Rules can only be listed by status of active or disabled.');
        }

        if ($order === null && !in_array($order, ['status', 'priority'])) {
            throw new EndpointException('Page Rules can only be ordered by status or priority.');
        }

        if ($direction === null && !in_array($direction, ['asc', 'desc'])) {
            throw new EndpointException('Direction of Page Rule ordering can only be asc or desc.');
        }

        if ($match === null && !in_array($match, ['all', 'any'])) {
            throw new EndpointException('Match can only be any or all.');
        }

        $query = [
            'status' => $status,
            'order' => $order,
            'direction' => $direction,
            'match' => $match
        ];

        $user = $this->adapter->get('zones/' . $zoneID . '/pagerules', $query, []);
        $body = json_decode($user->getBody());

        return $body->result;
    }

    public function getPageRuleDetails(string $zoneID, string $ruleID): \stdClass
    {
        $user = $this->adapter->get('zones/' . $zoneID . '/pagerules/' . $ruleID, [], []);
        $body = json_decode($user->getBody());
        return $body->result;
    }

    public function updatePageRule(
        string $zoneID,
        PageRulesTargets $target = null,
        PageRulesActions $actions = null,
        bool $active = null,
        int $priority = null
    ): bool {
        $options = [];

        if ($target !== null) {
            $options['targets'] = $target->getArray();
        }

        if ($actions !== null) {
            $options['actions'] = $actions->getArray();
        }

        if ($active !== null) {
            $options['status'] = $active == true ? 'active' : 'disabled';
        }

        if ($priority !== null) {
            $options['priority'] = $priority;
        }


        $query = $this->adapter->patch('zones/' . $zoneID . '/pagerules', [], $options);

        $body = json_decode($query->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }

    public function deletePageRule(string $zoneID, string $ruleID): bool
    {
        $user = $this->adapter->delete('zones/' . $zoneID . '/pagerules/' . $ruleID, [], []);

        $body = json_decode($user->getBody());

        if (isset($body->result->id)) {
            return true;
        }

        return false;
    }
}
