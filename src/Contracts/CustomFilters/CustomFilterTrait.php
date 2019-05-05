<?php

namespace Baka\Database\Contracts\CustomFilters;

use Baka\Database\CustomFilters\CustomFilters;
use Baka\Database\Model;
use Baka\Database\CustomFilters\Conditions;
use Baka\Database\Exception\CustomFilterException;

/**
 * Custom field class.
 */
trait CustomFilterTrait
{
    /**
     * Given a filter and it soptions save process the critiria
     * 
     * [
     *     [
     *          {
     *            "api_name": "Leads.Annual_Revenue",
     *              "comparator": "equal",
     *              "value": "333",
     *               "field_label": "Annual Revenue"
     *           },
     *           "and",
     *           {
     *               "api_name": "Leads.Campaign_Source",
     *               "comparator": "equal",
     *               "value": "${NOTEMPTY}",
     *               "field_label": "Campaign Name"
     *           }
     *       ]
     *   ]
     *
     * @param CustomFilters $filter
     * @param array $criterias
     * @return void
     */
    public function processsCriterias(CustomFilters $filter, array $criterias) : bool
    {
        for ($i = 0 ; $i < count($criterias) ; $i++) {
            //the last element of a criteria doesnt have a conditonal
            $conditional = array_key_exists($i + 1, $criterias) ? $criterias[$i + 1] : null;

            $customFilterCondition = new Conditions();
            $customFilterCondition->search_filter_id = $filter->getId();
            $customFilterCondition->position = $i;
            $customFilterCondition->value = $criterias[$i]['value'];
            $customFilterCondition->field = $criterias[$i]['field'];
            $customFilterCondition->comparator = $criterias[$i]['comparator'];
            $customFilterCondition->conditional = $conditional;
            $customFilterCondition->saveOrFail();
        }

        return true;
    }

    /**
     * Given the criteria update the filter
     *
     * @param CustomFilters $filter
     * @param array $criterias
     * @return boolean
     */
    public function updateCriterias(CustomFilters $filter, array $criterias): bool
    {
        //clean all the conditions
        $filter->conditions->delete();

        return $this->processsCriterias($filter, $criterias);
    }
}
