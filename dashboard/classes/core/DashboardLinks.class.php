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
    const ACCESS_DENIED_VIEW      = DASHBOARD_PATH . 'view/access_denied/';
    const NEW_CONTENT_VIEW        = DASHBOARD_PATH . 'view/new_content/';
    const EDIT_CONTENT_VIEW       = DASHBOARD_PATH . 'view/edit_content/';
    const DELETE_CONTENT_VIEW     = DASHBOARD_PATH . 'view/delete_content/';
    const MANAGE_CATEGORIES_VIEW  = DASHBOARD_PATH . 'view/manage_categories/';
    const NEW_CATEGORY_VIEW       = DASHBOARD_PATH . 'view/new_category/';
    const MANAGE_NOTES_VIEW       = DASHBOARD_PATH . 'view/manage_notes/';
    const NEW_NOTE_VIEW           = DASHBOARD_PATH . 'view/new_note/';
    const EDIT_NOTE_VIEW          = DASHBOARD_PATH . 'view/edit_note/';
    const DELETE_NOTE_VIEW        = DASHBOARD_PATH . 'view/delete_note/';
    const PREVIEW_CONTENT_VIEW    = DASHBOARD_PATH . 'view/preview_content/';
    const MANAGE_ACCOUNT_VIEW     = DASHBOARD_PATH . 'view/manage_account/';
    const PREFERENCES_VIEW        = DASHBOARD_PATH . 'view/preferences/';
    const HELP_VIEW               = DASHBOARD_PATH . 'view/help/';

    const PLUGIN_VIEW             = DASHBOARD_PATH . 'plugin_view/';


    //Admin section
    const REPORTING_VIEW          = DASHBOARD_PATH . 'view/reporting/';
    const MANAGE_USERS_VIEW       = DASHBOARD_PATH . 'view/manage_users/';
    const NEW_USER_VIEW           = DASHBOARD_PATH . 'view/new_user/';
    const SWITCH_USER_VIEW        = DASHBOARD_PATH . 'view/switch_user/';
    const EXPORT_VIEW             = DASHBOARD_PATH . 'view/export/';


    //These are actions
    const LOGOUT_ACTION           = DASHBOARD_PATH . 'action/logout/';
    const NEW_CONTENT_ACTION      = DASHBOARD_PATH . 'action/new_content/';
    const EDIT_CONTENT_ACTION     = DASHBOARD_PATH . 'action/edit_content/';
    const DELETE_CONTENT_ACTION   = DASHBOARD_PATH . 'action/delete_content/';
    const NEW_CATEGORY_ACTION     = DASHBOARD_PATH . 'action/new_category/';
    const NEW_NOTE_ACTION         = DASHBOARD_PATH . 'action/new_note/';
    const EDIT_NOTE_ACTION        = DASHBOARD_PATH . 'action/edit_note/';
    const DELETE_NOTE_ACTION      = DASHBOARD_PATH . 'action/delete_note/';
    const MANAGE_CATEGORIES_ACTION= DASHBOARD_PATH . 'action/update_category/';
    const MANAGE_ACCOUNT_ACTION   = DASHBOARD_PATH . 'action/manage_account/';

    //Admin section
    const MANAGE_USERS_ACTION     = DASHBOARD_PATH . 'action/manage_users/';
    const NEW_USER_ACTION         = DASHBOARD_PATH . 'action/new_user/';
    const DISABLE_USER_ACTION     = DASHBOARD_PATH . 'action/disable_user/';
    const SWITCH_USER_ACTION      = DASHBOARD_PATH . 'action/switch_user/';
    const EXPORT_ACTION           = DASHBOARD_PATH . 'action/export/';

}
