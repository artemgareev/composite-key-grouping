<?php

namespace GroupApp\Tests;

use PHPUnit\Framework\TestCase;
use GroupApp\GroupingService;

class GroupingServiceTest extends TestCase
{
    /**
     * @var GroupingService
     */
    private $groupingService;

    /**
     *
     */
    public function setUp()
    {
        $this->groupingService = new GroupingService();
    }

    /**
     * @dataProvider groupingServiceDataProvider
     *
     * @param array $inputData
     * @param array $outputData
     * @param array $groupingKeys
     * @param array $sumFields
     * @param string $expectedException
     */
    public function testGroupingService(
        array $inputData,
        array $outputData,
        array $groupingKeys,
        array $sumFields,
        string $expectedException = ''
    ) {
        if(!empty($expectedException)) {
            $this->expectException($expectedException);
        }
        $this->assertEquals(
            $outputData,
            $this->groupingService->groupByArrayKeys(
                $inputData,
                $groupingKeys,
                $sumFields
            )
        );
    }

    /**
     * @return array
     */
    public function groupingServiceDataProvider()
    {
        return
            [
                'complex grouping key' => [
                [
                    [
                        'merchant_id' => 1,
                        'advert_id' => 1,
                        'income' => 100,
                        'expenses' => 100,
                    ],
                    [
                        'merchant_id' => 1,
                        'advert_id' => 2,
                        'income' => 100,
                        'expenses' => 100,
                    ],
                    [
                        'merchant_id' => 1,
                        'advert_id' => 1,
                        'income' => 100,
                        'expenses' => 100,
                    ],
                    [
                        'merchant_id' => 2,
                        'advert_id' => 1,
                        'income' => 100,
                        'expenses' => 100,
                    ],
                    [
                        'merchant_id' => 2,
                        'advert_id' => 1,
                        'income' => 100,
                        'expenses' => 100,
                    ],
                ],
                [
                    [
                        'merchant_id' => 1,
                        'advert_id' => 1,
                        'income' => 200,
                        'expenses' => 200,
                    ],
                    [
                        'merchant_id' => 1,
                        'advert_id' => 2,
                        'income' => 100,
                        'expenses' => 100,
                    ],
                    [
                        'merchant_id' => 2,
                        'advert_id' => 1,
                        'income' => 200,
                        'expenses' => 200,
                    ],
                ],
                [
                    'merchant_id','advert_id'
                ],
                [],
            ],
                'data without aggregation keys specified' => [
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 300,
                            'expenses' => 300,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 200,
                            'expenses' => 200,
                        ],
                    ],
                    [
                        'merchant_id',
                    ],
                    [],
                ],
                'data with aggregation keys specified' => [
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 200,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [
                        'merchant_id',
                    ],
                    [
                        'income',
                    ],
                ],
                'no grouping keys specified' => [
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [],
                    [],
                ],
                'not existing key specified' => [
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [
                        [
                            'merchant_id' => 1,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                        [
                            'merchant_id' => 2,
                            'income' => 100,
                            'expenses' => 100,
                        ],
                    ],
                    [
                        "notExistingKey"
                    ],
                    [],
                    \LogicException::class
                ],
            ];
    }
}
