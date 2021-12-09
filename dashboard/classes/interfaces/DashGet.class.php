<?php

abstract class DashGet extends DashCoreClass {
    abstract public function getName();
    abstract public function requiresEditorRights();
    abstract public function requiresAdministratorRights();
    abstract public function getData($d);
}

?>
