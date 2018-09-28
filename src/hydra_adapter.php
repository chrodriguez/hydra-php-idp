<?php
class HydraAdapter {
  private $pdo;
  private $user_sql;
  private $auth_sql;
  public function __construct($opts) {
    $this->db = $opts['pdo'];
    $this->user_sql = $opts['user_sql'];
    $this->auth_sql = $opts['auth_sql'];
  }

  public function doLogin($username, $password) {
    $stm = $this->db->prepare($this->auth_sql);
    $stm->execute([':username' => $username, ':password' => $password]);
    $res = $stm->fetchAll(); 
    return (count($res) == 1);
  }

  public function getIdTokenData($username) {
    $stm = $this->db->prepare($this->user_sql);
    $stm->execute([':username' => $username]);
    $res = $stm->fetchAll();
    if (count($res) != 1) throw new Exception("request a session for a user that doesn't exists");
    $ret = array();
    foreach($res[0] as $k => $v){
      $ret[$k] = $v;
    }
    return $ret;
  }
}


