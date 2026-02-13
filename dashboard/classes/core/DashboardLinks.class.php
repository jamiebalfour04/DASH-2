<?php

class DashboardLinks extends DashCoreClass {

    //These are all dashboard subsections
    const ABOUT = 'about';


    //Install
    const INSTALL_MODE = 'install';
    const INSTALL = 'Install';

    //Dashboard front
    const DASHBOARD_VIEW          = DASHBOARD_PATH . 'view/home/';
    const LOGIN_VIEW              = DASHBOARD_PATH . 'login/';
    const LOGOUT_VIEW             = DASHBOARD_PATH . 'view/logout/';
    const SEARCH_FOR_POST_VIEW    = DASHBOARD_PATH . 'view/search/';
    const ACCESS_DENIED_VIEW      = DASHBOARD_PATH . 'view/access-denied/';
    const NEW_CONTENT_VIEW        = DASHBOARD_PATH . 'view/new-content/';
    const EDIT_CONTENT_VIEW       = DASHBOARD_PATH . 'view/edit-content/';
    const DELETE_CONTENT_VIEW     = DASHBOARD_PATH . 'view/delete-content/';
    const MANAGE_CATEGORIES_VIEW  = DASHBOARD_PATH . 'view/manage-categories/';
    const NEW_CATEGORY_VIEW       = DASHBOARD_PATH . 'view/new-category/';
    const MANAGE_NOTES_VIEW       = DASHBOARD_PATH . 'view/manage-notes/';
    const NEW_NOTE_VIEW           = DASHBOARD_PATH . 'view/new-note/';
    const EDIT_NOTE_VIEW          = DASHBOARD_PATH . 'view/edit-note/';
    const DELETE_NOTE_VIEW        = DASHBOARD_PATH . 'view/delete-note/';
    const PREVIEW_CONTENT_VIEW    = DASHBOARD_PATH . 'view/preview-content/';
    const MANAGE_ACCOUNT_VIEW     = DASHBOARD_PATH . 'view/manage-account/';
    const PREFERENCES_VIEW        = DASHBOARD_PATH . 'view/preferences/';
    const HELP_VIEW               = DASHBOARD_PATH . 'view/help/';

    const PLUGIN_VIEW             = DASHBOARD_PATH . 'plugin-view/';


    //Admin section
    const REPORTING_VIEW          = DASHBOARD_PATH . 'view/reporting/';
    const MANAGE_USERS_VIEW       = DASHBOARD_PATH . 'view/manage-users/';
    const NEW_USER_VIEW           = DASHBOARD_PATH . 'view/new-user/';
    const SWITCH_USER_VIEW        = DASHBOARD_PATH . 'view/switch-user/';
    const EXPORT_VIEW             = DASHBOARD_PATH . 'view/export/';


    //These are actions
    const LOGOUT_ACTION           = DASHBOARD_PATH . 'action/logout/';
    const NEW_CONTENT_ACTION      = DASHBOARD_PATH . 'action/new-content/';
    const EDIT_CONTENT_ACTION     = DASHBOARD_PATH . 'action/edit-content/';
    const DELETE_CONTENT_ACTION   = DASHBOARD_PATH . 'action/delete-content/';
    const NEW_CATEGORY_ACTION     = DASHBOARD_PATH . 'action/new-category/';
    const NEW_NOTE_ACTION         = DASHBOARD_PATH . 'action/new-note/';
    const EDIT_NOTE_ACTION        = DASHBOARD_PATH . 'action/edit-note/';
    const DELETE_NOTE_ACTION      = DASHBOARD_PATH . 'action/delete-note/';
    const MANAGE_CATEGORIES_ACTION= DASHBOARD_PATH . 'action/update-category/';
    const MANAGE_ACCOUNT_ACTION   = DASHBOARD_PATH . 'action/manage-account/';

    //Admin section
    const MANAGE_USERS_ACTION     = DASHBOARD_PATH . 'action/manage-users/';
    const NEW_USER_ACTION         = DASHBOARD_PATH . 'action/new-user/';
    const DISABLE_USER_ACTION     = DASHBOARD_PATH . 'action/disable-user/';
    const SWITCH_USER_ACTION      = DASHBOARD_PATH . 'action/switch-user/';
    const EXPORT_ACTION           = DASHBOARD_PATH . 'action/export/';

}
