<?php

return [
    'name' => 'StudentSponsorship',
    
    // Grade to age mapping for Sri Lankan education system
    'grade_age_mapping' => [
        1 => ['min' => 5, 'max' => 6],
        2 => ['min' => 6, 'max' => 7],
        3 => ['min' => 7, 'max' => 8],
        4 => ['min' => 8, 'max' => 9],
        5 => ['min' => 9, 'max' => 10],
        6 => ['min' => 10, 'max' => 11],
        7 => ['min' => 11, 'max' => 12],
        8 => ['min' => 12, 'max' => 13],
        9 => ['min' => 13, 'max' => 14],
        10 => ['min' => 14, 'max' => 15],
        11 => ['min' => 15, 'max' => 16], // O/L
        12 => ['min' => 16, 'max' => 17], // A/L1
        13 => ['min' => 17, 'max' => 18], // A/L2
        14 => ['min' => 18, 'max' => 19], // A/L Final
    ],
    
    'school_grades' => [
        '1' => 'Grade 1',
        '2' => 'Grade 2',
        '3' => 'Grade 3',
        '4' => 'Grade 4',
        '5' => 'Grade 5',
        '6' => 'Grade 6',
        '7' => 'Grade 7',
        '8' => 'Grade 8',
        '9' => 'Grade 9',
        '10' => 'Grade 10',
        '11' => 'O/L (Grade 11)',
        '12' => 'A/L1 (Grade 12)',
        '13' => 'A/L2 (Grade 13)',
        '14' => 'A/L Final (Grade 14)',
    ],
    
    'school_types' => [
        'Type 1AB' => 'Type 1AB',
        'Type 1C' => 'Type 1C',
        'Type 2' => 'Type 2',
        'Type 3' => 'Type 3',
    ],
];
