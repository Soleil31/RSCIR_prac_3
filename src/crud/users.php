<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
header('Content-Type: application/json');
$con = new mysqli('MYSQL', 'user', 'password', 'dataDB');
$answer = array();
switch ($requestMethod) {
    case 'GET':
        if (empty(isset($_GET['id']))) {
            $result = $con->query("SELECT * FROM users;");
            while ($row = $result->fetch_assoc()) {
                $answer[] = $row;
            }
        } else {
            $query_result = $con->query("SELECT * FROM users WHERE ID = " . $_GET['id'] . ";");
            $result = $query_result->fetch_row();
            $answer = $result;
        }
        if (!empty($result)) {
            http_response_code(200);
            echo json_encode($answer);
        } else {
            http_response_code(204);
        }
        break;
    case 'POST':
        $json = file_get_contents('php://input');
        $user = json_decode($json);
        if (!empty($user->{'name'}) && !empty($user->{'email'}) && !empty($user->{'phone'})) {
            $name = $user->{'name'};
            $email = $user->{'email'};
            $phone = $user->{'phone'};
            $query_result = $con->query("SELECT * FROM users WHERE name='" . $name . "'");
            if (!empty($result)) {
                http_response_code(409);
            } else {
                $stmt = $con->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $name, $email, $phone);
                $stmt->execute();
                http_response_code(201);
            }
        } else {
            http_response_code(422);
        }
        break;
    case 'PUT':
        $json = file_get_contents('php://input');
        $user = json_decode($json);
        if (!empty($user->{'name'}) && !empty($user->{'email'})&& !empty($user->{'phone'})) {
            if (empty(isset($_GET['id']))) {
                http_response_code(422);
            } else {
                $query_result = $con->query("SELECT * FROM users WHERE ID='" . $_GET['id'] . "'");
                $result = $query_result->fetch_row();
                if (!empty($result)) {
                    $query_result = $con->query("SELECT * FROM users WHERE name='" . $user->{'name'} . "' AND ID!='" . $_GET['id'] . "'");
                    $result = $query_result->fetch_row();
                    if (!empty($result)) {
                        http_response_code(409);
                    } else {
                        $con->query("UPDATE users SET name='" . $user->{'name'} . "', email='" . $user->{'email'} . "', phone='" . $user->{'phone'} . "' WHERE ID='" . $_GET['id'] . "'");
                        http_response_code(200);
                    }
                } else {
                    http_response_code(204);
                }
            }
        } else {
            http_response_code(422);
        }
        break;
    case 'DELETE':
        if (empty(isset($_GET['id']))) {
            http_response_code(422);
        } else {
            $query_result = $con->query("SELECT * FROM users WHERE ID='" . $_GET['id'] . "'");
            $result = $query_result->fetch_row();
            if (!empty($result)) {
                $query_result = $con->query("DELETE FROM users WHERE ID='" . $_GET['id'] . "'");
                http_response_code(204);
            } else {
                http_response_code(204);
            }
        }
        break;
    default:
        http_response_code(405);
        break;
}
?>