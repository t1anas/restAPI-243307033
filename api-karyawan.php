<?php

require_once "connection.php";

if (isset($_GET['function']) && function_exists($_GET['function'])) {
    $_GET['function']();
}

function getKaryawan() {
    global $connect;

    $query = mysqli_query($connect, "SELECT * FROM tbl_karyawan");
    $data = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    $response = [
        'status' => 200,
        'message' => 'Success',
        'data' => $data
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}

function insertKaryawan()
{
    global $connect;

    $check = array(
        'id'            => '',
        'nama'          => '',
        'jenis_kelamin' => '',
        'handphone'     => '',
        'alamat'        => ''
    );

    $check_match = count(array_intersect_key($_POST, $check));

    if ($check_match == count($check)) {
        // Menggunakan prepared statement agar aman dari error kutip dan SQL Injection
        $stmt = mysqli_prepare(
            $connect,
            "INSERT INTO tbl_karyawan (id, nama, jenis_kelamin, handphone, alamat) VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $_POST['id'],
            $_POST['nama'],
            $_POST['jenis_kelamin'],
            $_POST['handphone'],
            $_POST['alamat']
        );

        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $response = array(
                'status'  => 201,
                'message' => 'Insert Success'
            );
        } else {
            $response = array(
                'status'  => 400,
                'message' => 'Insert Failed.'
            );
        }

        mysqli_stmt_close($stmt);
    } else {
        $response = array(
            'status'  => 405,
            'message' => 'Wrong Parameter'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function getKaryawanById() {
    global $connect;

    $id = $_GET['id'] ?? 0;

    $stmt = mysqli_prepare(
        $connect,
        "SELECT * FROM tbl_karyawan WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $response = [
            'status' => 200,
            'message' => 'Success',
            'data' => $data
        ];
    } else {
        $response = [
            'status' => 404,
            'message' => 'No Data Found'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function updateKaryawan() {
    global $connect;

    $id = $_GET['id'] ?? 0;

    $nama = $_POST['nama'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $handphone = $_POST['handphone'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    if ($id && $nama && $jenis_kelamin && $handphone && $alamat) {

        $stmt = mysqli_prepare(
            $connect,
            "UPDATE tbl_karyawan
            SET nama = ?, jenis_kelamin = ?,
            handphone = ?, alamat = ?
            WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $nama,
            $jenis_kelamin,
            $handphone,
            $alamat,
            $id
        );

        if (mysqli_stmt_execute($stmt) &&
            mysqli_stmt_affected_rows($stmt) >= 0) {

            $response = [
                'status' => 200,
                'message' => 'Update Success'
            ];

        } else {
            $response = [
                'status' => 400,
                'message' => 'Update Failed'
            ];
        }

    } else {
        $response = [
            'status' => 405,
            'message' => 'Wrong Parameter'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

function deleteKaryawan() {
    global $connect;

    $id = $_GET['id'] ?? 0;

    if ($id) {

        $stmt = mysqli_prepare(
            $connect,
            "DELETE FROM tbl_karyawan WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt) &&
            mysqli_stmt_affected_rows($stmt) > 0) {

            $response = [
                'status' => 200,
                'message' => 'Delete Success'
            ];

        } else {
            $response = [
                'status' => 404,
                'message' => 'Delete Failed'
            ];
        }

    } else {
        $response = [
            'status' => 405,
            'message' => 'Wrong Parameter'
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>