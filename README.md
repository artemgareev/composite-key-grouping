# composite-key-grouping
## Usage
```
<?php
$dataToGroup = [
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
        'expenses' => 1,
    ],
];
$groupingService = new GroupingService();
$output = $groupingService->groupByArrayKeys(
    $dataToGroup,
    ['merchant_id','advert_id']
);
//$output =
//[
//    [
//        'merchant_id' => 1,
//        'advert_id' => 1,
//        'income' => 200,
//        'expenses' => 200,
//    ],
//    [
//        'merchant_id' => 1,
//        'advert_id' => 2,
//        'income' => 100,
//        'expenses' => 100,
//    ]
//];
```
