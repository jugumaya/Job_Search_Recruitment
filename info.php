<?php

header('Content-Type: application/json');


$info = [
    'name' => 'Afraim Uzzaman Romeo', 
    'id' => '2211512',
    'personal_notion_page' => 'https://www.notion.so/3895793f65df44559b314d80de7241a3', 
    'personal_group_page_notion' => 'https://www.notion.so/Romeo-Contribution-1abd06383e5c803ba7f5ce31c262772f', 
    'github_id' => 'Afraim404',
    'project_github_link' => 'https://github.com/Diya-100/Job-finding-website-social-media.git' 
];


echo json_encode($info, JSON_PRETTY_PRINT);
?>