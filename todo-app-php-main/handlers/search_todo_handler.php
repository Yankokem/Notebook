<?php

include "../database/database.php";

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $search = "%" . $_POST['search'] . "%";
        $stmt = $conn->prepare("SELECT * FROM todo WHERE title LIKE ?");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $res = $stmt->get_result();

        $todos = [];
        while ($row = $res->fetch_assoc()) {
            $todos[] = $row;
        }

        echo json_encode($todos);
    }
} catch (\Exception $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
