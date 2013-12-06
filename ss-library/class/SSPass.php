<?php
class PPass
{
    protected $_saltChars = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected $_saltFormat = '$2a$08$%s$'; // Use blowfish

    public function __construct() {}

    public function salt() {
        $saltChars = str_shuffle($this->_saltChars);
        return substr($saltChars, 0, strlen($saltChars));
    }

    public function hash($input) {
        $salt = sprintf($this->_saltFormat, $this->salt());
        $hash = crypt($input, $salt);
        return $hash;
    }

    public function validate($input, $hash) {
        return crypt($input, $hash) == $hash;
    }
}