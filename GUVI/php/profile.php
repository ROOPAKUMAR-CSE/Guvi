<?php

// Connect to MongoDB
$mongo = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Fetch data from MongoDB
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $param = $_GET['email'] ?? null;
    if ($param) {
        $filter = ['email' => $param];
        $options = ['projection' => ['_id' => 0]];
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $mongo->executeQuery('guvi.userDetails', $query);

        $results = [];
        foreach ($cursor as $document) {
            $results[] = $document;
        }

        if (!empty($results)) {
            echo json_encode($results);
        } else {
            echo "No data found for email: $param";
        }
    } else {
        echo "Email parameter missing";
    }
}

// Update data in MongoDB
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $jsonData = file_get_contents('php://input');
    $putData = json_decode($jsonData, true);
    $email = $putData['email'] ?? '';

    if ($email) {
        $bulk = new MongoDB\Driver\BulkWrite();
        $filter = ['email' => $email];
        $update = ['$set' => $putData];
        $bulk->update($filter, $update);
        $mongo->executeBulkWrite('guvi.userDetails', $bulk);

        echo "Success: Updated data for email: $email";
    } else {
        echo "Email parameter missing";
    }
}

// Save profile in MongoDB
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonData = file_get_contents('php://input');
    $postData = json_decode($jsonData, true);
    $id = $postData["_id"] ?? '';

    if ($id) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(['_id' => $id], ['$set' => $postData], ['multi' => false, 'upsert' => false]);
        $mongo->executeBulkWrite('mydb.user', $bulk);
        
        echo "Success: Profile updated for ID: $id";
    } else {
        echo "User ID parameter missing";
    }
}
