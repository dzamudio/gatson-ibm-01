<?php
namespace Core;

use http\Params;
use mysqli;

class DatabaseHandler
{
    private $serverName = "localhost";
    private $username = "root";
    private $password = null;
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->serverName, $this->username, $this->password);

        if ($this->conn->connect_error) {
            error_log('DatabaseHandler.php - DatabaseHandler->__construct(): Connection error on line:' . __LINE__ . ' - ' . $this->conn->connect_error);
        }
    }

    public function saveWatsonNLPData()
    {

    }
}