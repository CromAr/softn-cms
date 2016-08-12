<?php

/**
 * Modulo del modelo user.
 * Gestiona la lista de Usuarios.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona la lista con todos los usuarios de la base de datos.
 *
 * @author Nicolás Marulanda P.
 */
class Users {

    /**
     * Lista de usuarios, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $users;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->users = [];
    }

    /**
     * 
     * @return Users
     */
    public static function selectAll() {
        return self::select();
    }

    /**
     * 
     * @param type $val
     * @return Users
     */
    public static function selectByName($val) {
        $value = "%$val%";
        $parameter = ':' . User::USER_NAME;
        $where = User::USER_NAME . " LIKE $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, \PDO::PARAM_STR);
        return self::select($where, $prepare);
    }

    /**
     * 
     * @param type $value
     * @return Users
     */
    public static function selectByRegistred($value) {
        return self::selectBy($value, User::USER_REGISTRED);
    }

    /**
     * 
     * @param type $value
     * @return Users
     */
    public static function selectByRol($value) {
        return self::selectBy($value, User::USER_ROL, \PDO::PARAM_INT);
    }

    /**
     * 
     * @param type $value
     * @param type $column
     * @param type $dataType
     * @return Users
     */
    private static function selectBy($value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);
        return self::select($where, $prepare);
    }

    /**
     * 
     * @param type $where
     * @param type $prepare
     * @param type $columns
     * @param type $limit
     * @param type $orderBy
     * @return Users
     */
    private static function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        $select = $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
        $users = new Users();
        $users->addUsers($select);
        return $users;
    }

    /**
     * Metodo que obtiene todos los usuarios.
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Metodo que obtiene, segun su ID, un usuario.
     * @param int $id
     * @return Users
     */
    public function getUser($id) {
        return $this->users[$id];
    }

    /**
     * Metodo que agrega un usuario a la lista.
     * @param User $user
     */
    public function addUser(User $user) {
        $this->users[$user->getID()] = $user;
    }

    /**
     * Metodo que obtiene un array con los datos de los usuarios y los agrega a la lista.
     * @param array $user
     */
    public function addUsers($user) {
        foreach ($user as $value) {
            $this->addUser(new User($value));
        }
    }

    /**
     * 
     * @return int
     */
    public function count() {
        $db = DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        $columns = 'COUNT(*) AS count';
        $select = $db->select($table, $fetch, '', [], $columns);
        return $select[0]['count'];
    }

}