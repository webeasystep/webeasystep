<?php

function getTargetItem($lastAccessedItem, $flatItems, $completedItemIds) {
    if (!$lastAccessedItem) {
        return $flatItems[0]['id'];
    }

    $lastIndex = -1;
    foreach ($flatItems as $index => $item) {
        if ($item['id'] == $lastAccessedItem->item_id) {
            $lastIndex = $index;
            break;
        }
    }

    if ($lastIndex !== -1) {
        $targetItemId = $lastAccessedItem->item_id;
        if ($lastAccessedItem->is_completed == 1) {
            for ($i = $lastIndex + 1; $i < count($flatItems); $i++) {
                if (!in_array($flatItems[$i]['id'], $completedItemIds)) {
                    $targetItemId = $flatItems[$i]['id'];
                    break;
                }
            }
        }
        return $targetItemId;
    }
    return $flatItems[0]['id'];
}

$flatItems = [
    ['id' => 101, 'title' => 'Lesson 1'],
    ['id' => 102, 'title' => 'Lesson 2'],
    ['id' => 103, 'title' => 'Lesson 3'],
    ['id' => 104, 'title' => 'Lesson 4'],
    ['id' => 105, 'title' => 'Lesson 5'],
];

$scenarios = [
    [
        'name' => '1. First time opening the course',
        'lastAccessed' => null,
        'completed' => [],
        'expected' => 101
    ],
    [
        'name' => '2. Part-way through Lesson 2 (not completed)',
        'lastAccessed' => (object)['item_id' => 102, 'is_completed' => 0],
        'completed' => [101],
        'expected' => 102
    ],
    [
        'name' => '3. Just completed Lesson 2',
        'lastAccessed' => (object)['item_id' => 102, 'is_completed' => 1],
        'completed' => [101, 102],
        'expected' => 103
    ],
    [
        'name' => '4. Going back to a previous lesson (Reviewed Lesson 1, already completed 1, 2, 3)',
        'lastAccessed' => (object)['item_id' => 101, 'is_completed' => 1],
        'completed' => [101, 102, 103],
        'expected' => 104
    ],
    [
        'name' => '5. Reaching the last unit (Completed 1-4, starting 5)',
        'lastAccessed' => (object)['item_id' => 104, 'is_completed' => 1],
        'completed' => [101, 102, 103, 104],
        'expected' => 105
    ],
    [
        'name' => '6. Completed the entire course (Last accessed is 5, completed)',
        'lastAccessed' => (object)['item_id' => 105, 'is_completed' => 1],
        'completed' => [101, 102, 103, 104, 105],
        'expected' => 105
    ],
    [
        'name' => '7. Skipped around (Completed 1 and 3, last accessed 3)',
        'lastAccessed' => (object)['item_id' => 103, 'is_completed' => 1],
        'completed' => [101, 103],
        'expected' => 104
    ],
    [
        'name' => '8. Last accessed item does not exist in current flatItems (e.g. lesson deleted)',
        'lastAccessed' => (object)['item_id' => 999, 'is_completed' => 1],
        'completed' => [999],
        'expected' => 101
    ],
];

$passed = 0;
echo "Running Course Completion Progress Tests...\n";
echo str_repeat("-", 50) . "\n";
foreach ($scenarios as $s) {
    $result = getTargetItem($s['lastAccessed'], $flatItems, $s['completed']);
    $status = ($result === $s['expected']) ? "PASS" : "FAIL (Got $result, Expected {$s['expected']})";
    echo "[$status] {$s['name']}\n";
    if ($result === $s['expected']) $passed++;
}
echo str_repeat("-", 50) . "\n";
echo "Total Passed: $passed / " . count($scenarios) . "\n";
