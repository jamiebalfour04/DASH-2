<?php
/*
* The actual request on the HTTP header
*/
class DashRequest extends DashCoreClass {
    private $mode;
    private $page;
    private $data;
    private $post;


    public function __construct($mode, $page, $data) {
        $this->mode = $mode;
        $this->page = $page;
        $this->data = $data;
        $this->post = $_POST;
    }

    public function getMode() {
        return $this->mode;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getData() {
        return $this->type;
    }

    public function getPost($v) {
        $p = $this->post;
        if (isset($p[$v])) {
            return $this->post[$v];
        }
        return "";
    }

    public function __toString()
    {
        return ($this->mode . ' ' . $this->page . ' ' . $this->data);
    }
}
