<?php
namespace models;
require_once __DIR__ . '/../config/Connection.php';
use config\Connection;
use PDO;
class User
{
        public static function get()
        {
        // get all users
        }
        public static function create($data)
        {
        // insert user
        }
        public static function find($id)
        {
        // find user by id
        }
        public static function update($data)
        {
        // update user by id
}