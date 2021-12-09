<?php

abstract class DashAction extends DashCoreClass {
    abstract public function getName();
    abstract public function requiresLogin();
    abstract public function requiresEditorRights();
    abstract public function requiresAdministratorRights();
    abstract public function performAction($d);
}

?>
