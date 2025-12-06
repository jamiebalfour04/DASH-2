# DASH

<p>
  DASH or <strong>D</strong>ash: <strong>A</strong>daptable, <strong>S</strong>ecure, and <strong>H</strong>igh-performance CMS is a new CMS I use on my website and many of the websites I build and host. Its name gives an idea of what it is for and how it can be used. 

<p>
  The move from hosting the only copy of DASH v2 on my website, as with Dash 1.x, is intended to improve DASH further. 
</p>

<p>
  DASH used to be hosted on GitHub, but since I have created a new GitHub, you cannot see the old commits.
</p>

<h2>Setup</h2>

<p>
  Setup is straightforward and secure. You first need to open the config.php file:
</p>
<pre>
$configs = array(
  "blogs" => array(
    "name"                  => "Personal Blog",
    "content_location"      => "/blog/",
    "assets_directory"      => "/blog/assets/",
    "dashboard_path"        => "/dash/dashboard/",
    "database_host"         => "localhost",
    "database_user"         => "jamesbond007",
    "database_password"     => "007",
    "database_name"         => "blogs",
    "database_tables"       => array(
        "POSTS"             => "Blog_Posts",
        "CATEGORIES"        => "Blog_Categories",
        "USERS"             => "Blog_Users",
        "NOTES"             => "Blog_User_Notes"
    )
  )
);
</pre>

<p>You'll need to adapt this to fit with your own setup. You can also add a shared config if you've got multiple configs on your website. For example, mine looks like this:</p>
<pre>
$configs = array(
  "shared_config" => array(
    "dashboard_path"    => $dp,
    "database_host"     => "localhost",
    "database_user"     => "myDatabaseUser",
    "database_password" => "***************",
    "database_name"     => "Blogs",
    "onesignal_app_id"  => "***************",
    "onesignal_rest_id" => "***************"
  ),
  "blog" => array(
    "name" => "Personal Blog",
    "content_location"  => "/blog/",
    "assets_directory"  => "/blog/assets/",
    "database_tables"   => array(
      "POSTS"      => "Personal_Blog_Posts",
      "CATEGORIES" => "Personal_Blog_Categories",
      "USERS"      => "Blog_Users",
      "NOTES"      => "User_Notes"
    ),
    "dashboard"         => array(
      "custom_css" => "/assets/css/style.css"
    )
  ),
  "articles" => array(
    "name" => "Articles",
    "content_location"  => "/articles/",
    "assets_directory"  => "/articles/assets/",
    "database_tables"   => array(
      "POSTS"      => "Articles_Posts",
      "CATEGORIES" => "Articles_Categories",
      "USERS"      => "Blog_Users",
      "NOTES"      => "User_Notes"
    ),
  ),
  "reviews" => array(
    "name" => "Reviews",
    "content_location"  => "/reviews/",
    "assets_directory"  => "/reviews/assets/",
    "database_tables"   => array(
      "POSTS"      => "Reviews_Posts",
      "CATEGORIES" => "Reviews_Categories",
      "USERS"      => "Blog_Users",
      "NOTES"      => "User_Notes"
    )
  )
);
</pre>
<h2>Remote login</h2>
<p>
  Remote login allows another script to log a user in by simply including the following code (assuming you have the DASH installation in the root of the website):
</p>
<pre>
include $_SERVER['DOCUMENT_ROOT'] . '/dash/dashboard/login/login_functions.php';  
loginUser("blog",
    array(
      "user_id" => 1,
      "username" => "myUsername",
      "password" => "",
      "password_salted" => 1,
      "role" => 0,
      "login_attempts" => 0
    )
);
</pre>
<p>
  The first parameter is the config to use (in my config, I have <em>blog</em> as an individual config, as you can see above). The second parameter requires you 
  to provide all the data to be put into the session variable.
</p>
<h2>Creating the tables</h2>
<p>
  Whilst DASH will install the tables for you, I have provided a simple MySQL example for creating the table manually (you can find a file in the installation for this too):
</p>
<pre>
CREATE TABLE `Reviews_Posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `introduction` text NOT NULL,
  `banner` text NOT NULL,
  `content` longtext NOT NULL,
  `date` datetime NOT NULL,
  `category` int(11) NOT NULL,
  `poster` int(11) NOT NULL,
  `tags` text NOT NULL,
  `classes` text NOT NULL,
  `friendly_name` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL
)

ALTER TABLE `Reviews_Posts`
  ADD PRIMARY KEY (`post_id`),
  ADD UNIQUE KEY `friendly_name` (`friendly_name`),
  ADD UNIQUE KEY `title` (`title`);

ALTER TABLE `Reviews_Posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
</pre>
