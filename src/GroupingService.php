<?php

namespace GroupApp;

class GroupingService
{
    private $summingFields;

    /**
     * @param array $dataToGroup
     * @param array $groupingKeys
     *
     * @param array $summingFields
     *
     * @return array
     */
    public function groupByArrayKeys(
        array $dataToGroup,
        array $groupingKeys,
        array $summingFields = []
    ) {
        if (empty($groupingKeys)) {
            return $dataToGroup;
        }
        $this->summingFields = $summingFields;

        $keysCombinations = $this->getKeysUniqueCombinations($dataToGroup, $groupingKeys);
        $dataByGroups = [];

        foreach ($dataToGroup as $dataSingleLine) {
            $groupKey = $this->getDataLineGroupKey($dataSingleLine, $keysCombinations);
            $dataByGroups[$groupKey][] = $dataSingleLine;
        }

        return $this->mergeDataGroups($dataByGroups);
    }

    /**
     * @param array $dataByGroups
     *
     * @return array
     */
    private function mergeDataGroups(array $dataByGroups)
    {
        $mergedDataGroups = [];
        foreach ($dataByGroups as $singleDataGroup) {
            $mergedDataGroup = array_pop($singleDataGroup);
            foreach ($singleDataGroup as $singleGroupLine) {
                $mergedDataGroup = $this->mergeSingleLine(
                    $mergedDataGroup,
                    $singleGroupLine
                );
            }
            $mergedDataGroups[] = $mergedDataGroup;
        }

        return $mergedDataGroups;
    }

    /**
     *
     * @param array $dataLine
     * @param array $dataLineToMerge
     *
     * @return array
     */
    private function mergeSingleLine(array $dataLine, array $dataLineToMerge)
    {
        foreach ($dataLine as $key => $value) {
            if (strpos($key, 'id')) {
                continue;
            }

            if (!empty($this->summingFields)) {
                if (in_array($key, $this->summingFields)) {
                    if (isset($dataLineToMerge[$key])) {
                        $dataLine[$key] = (int)$value + (int)$dataLineToMerge[$key];
                    }
                }
            } elseif (is_numeric($value)) {
                $dataLine[$key] = (int)$value + (int)$dataLineToMerge[$key];
            }
        }

        return $dataLine;
    }

    /**
     * @param array $dataToGroup
     * @param array $groupingKeys
     *
     * @return array
     */
    private function getKeysUniqueCombinations(array $dataToGroup, array $groupingKeys): array
    {
        $keysCombinations = [];

        foreach ($dataToGroup as $dataLine) {
            $combinationKey = '';
            $keysCombination = [];
            foreach ($dataLine as $dataLineKey => $dataLineValue) {
                if (in_array($dataLineKey, $groupingKeys)) {
                    $combinationKey .= $dataLineValue;
                    $keysCombination[$dataLineKey] = $dataLineValue;
                }
            }
            if(empty($keysCombination)){
                throw new \LogicException("There is no data with such grouping keys");
            }
            $keysCombinations[$combinationKey] = $keysCombination;
        }

        return array_values($keysCombinations);
    }

    /**
     * @param array $dataSingleLine
     * @param array $keysCombinations
     *
     * @return int
     */
    private function getDataLineGroupKey(array $dataSingleLine, array $keysCombinations)
    {
        for ($combinationNumber = 0; $combinationNumber < count($keysCombinations); $combinationNumber++) {
            $expectedHits = count($keysCombinations[$combinationNumber]);
            $combinationsHits = 0;
            foreach ($keysCombinations[$combinationNumber] as $key => $value) {
                if ($dataSingleLine[$key] === $value) {
                    $combinationsHits++;
                }
            }
            if ($combinationsHits === $expectedHits) {
                return $combinationNumber;
            }
        }
        throw new \LogicException('Unexpected data!');
    }
}
