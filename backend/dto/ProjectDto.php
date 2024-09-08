<?php

namespace app\dto;

use app\models\Project;

class ProjectDto
{
    public $id;
    public $title;
    public $text;
    public $place;

    public function __construct(Project $model) {
        $this->id = $model->id;
        $this->title = $model->title;
        $this->text = $model->text;
        $this->place = $model->place;
    }

}