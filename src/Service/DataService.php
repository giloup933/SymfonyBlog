<?php


namespace App\Service;


class DataService
{
    private $conn;
    public function __construct() {
        $this->conn = $this->connectDB();
        if ($this->conn->connect_errno) {
            echo "Failed to connect to mysqli: " . $this->conn->mysqli_connect_error();
            return -1;
            //die("Connection failed: " . $conn->connect_error);
        }
    }

    public function connectDB() {
        $servername = "";
        $username = "";
        $password = "";
        $dbname = "";
        $port = 3306;

        return new \mysqli($servername, $username, $password, $dbname, $port);
    }

    public function execQuery($sql) {
        $ret = [];
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            array_push($ret, $row);
        }
        #echo(print_r($ret));
        return $ret;
    }

    public function readPost(int $id) {
        $query = "SELECT * FROM posts WHERE id=" . $id;
        return $this->execQuery($query);
    }

    public function readComments(int $postid) {
        $query = "SELECT * FROM comments WHERE postid=" . $postid;
        #$query = "SELECT * FROM comments";
        $comments = $this->execQuery($query);
        return $comments;
    }

    public function newPost(string $title, string $body) {
        $query = "INSERT INTO posts (title, body, time, userid, username) VALUES ('" . $title . "', '" . $body . "', '2020-12-21 07:17:00', 13, 'PartyBoi')";
        return $this->execQuery($query);
    }

    public function leaveComment(int $postid, string $title, string $body) {
        $query = "INSERT INTO comments (title, body, time, userid, username, postid) VALUES ('" . $title . "', '" . $body . "', '2020-12-21 07:17:00', 13, 'PartyBoi', " . $postid . ")";
        return $this->execQuery($query);
    }

    public function searchPosts(string $text) {
        $query = "SELECT * FROM comments WHERE body IS LIKE '%" . $text . "%'";
        return $this->execQuery($query);
    }
}