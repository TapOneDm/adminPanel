<?php

namespace app\dto;

use app\models\User;

class UserDto
{
    public $id;
    public $email;
    public $isActive;

    public function __construct(User $model) {
        $this->id = $model->id;
        $this->email = $model->email;
        $this->isActive = $model->is_activated;
    }

}