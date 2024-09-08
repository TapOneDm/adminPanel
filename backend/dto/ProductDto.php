<?php

namespace app\dto;

use app\models\Product;

class ProductDto
{
    public $id;
    public $record_status;
    public $show_on_website;
    public $title;
    
    public $text;
    public $price;
    public $sort_order;

    public function __construct(Product $model) {
        $this->id = $model->id;
        $this->record_status = $model->record_status;
        $this->show_on_website = $model->show_on_website;
        $this->title = $model->title;
        $this->text = $model->text;
        $this->price = $model->price;
        $this->sort_order = $model->sort_order;
    }

}