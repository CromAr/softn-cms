<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\models\admin\User;
use SoftnCMS\models\admin\Users;
use SoftnCMS\models\admin\UserInsert;
use SoftnCMS\models\admin\UserDelete;
use SoftnCMS\models\admin\UserUpdate;

/**
 * Description of UserController
 *
 * @author Nicolás Marulanda P.
 */
class UserController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    public function update($id) {
        return ['data' => $this->dataUpdate($id)];
    }

    public function delete($id) {
        return ['data' => $this->dataDelete($id)];
    }

    public function insert() {
        return ['data' => $this->dataInsert()];
    }

    private function dataIndex() {
        $users = Users::selectAll();
        $output = $users->getUsers();
        return ['users' => $output];
    }

    private function dataUpdate($id) {
        $user = User::selectByID($id);

        if (empty($user)) {
            header('Location: ' . \LOCALHOST . 'admin/user');
            exit();
        }

        if (filter_input(\INPUT_POST, 'update')) {
            $dataInput = $this->getDataInput();

            if ($dataInput['userPass'] == $dataInput['userPassR']) {
                $update = new UserUpdate($user, $dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);
                $user = $update->update();
            }
        }
        return [
            'user' => $user,
            'actionUpdate' => \TRUE
        ];
    }

    private function dataDelete($id) {
        $delete = new UserDelete($id);
        $delete->delete();
        return $this->dataIndex();
    }

    private function dataInsert() {
        if (filter_input(\INPUT_POST, 'publish')) {
            $dataInput = $this->getDataInput();
            if ($dataInput['userPass'] == $dataInput['userPassR']) {
                $insert = new UserInsert($dataInput['userLogin'], $dataInput['userName'], $dataInput['userEmail'], $dataInput['userPass'], $dataInput['userRol'], $dataInput['userUrl']);
                header('Location: ' . \LOCALHOST . 'admin/user/update/' . $insert->insert());
                exit();
            }
        }

        return [
            'user' => User::defaultInstance(),
            'actionUpdate' => \FALSE
        ];
    }

    private function getDataInput() {
        return [
            'userLogin' => \filter_input(\INPUT_POST, 'userLogin'),
            'userName' => \filter_input(\INPUT_POST, 'userName'),
            'userEmail' => \filter_input(\INPUT_POST, 'userEmail'),
            'userPass' => \filter_input(\INPUT_POST, 'userPass'),
            'userPassR' => \filter_input(\INPUT_POST, 'userPassR'),
            'userRol' => \filter_input(\INPUT_POST, 'userRol'),
            'userUrl' => \filter_input(\INPUT_POST, 'userUrl'),
        ];
    }

}