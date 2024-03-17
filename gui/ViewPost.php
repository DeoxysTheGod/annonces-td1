<?php
namespace gui;

include_once "View.php";

class ViewPost extends View
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout);

        $this->title= $presenter->getCurrentPostTitle();

        $this->content = $presenter->getCurrentPostHTML();
    }
}