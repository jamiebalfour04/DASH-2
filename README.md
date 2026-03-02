<h1>DASH-2 — Adaptable, Secure, High-Performance CMS</h1>

<p>
  <strong>DASH-2</strong> is my own PHP-based Content Management System (CMS) built to be
  <strong>powerful</strong>, <strong>fast</strong>, and <strong>easy to run</strong>.
  It’s designed for real-world websites where you want a clean admin experience, straightforward configuration,
  and the freedom to extend the platform without hacking core files.
</p>

<p>DASH or Dash: Adaptable, Secure and High-performance CMS is a new CMS I use on my personal website and on many of the websites I build and host. Its name gives an idea of what it is for and how it can be used. This iteration is version 2.0.</p>

<p>The move from hosting the only copy of DASH v2 on my website, as in Dash 1.x, is intended to help DASH improve more quickly and further.</p>

<p>
  One of the big strengths of DASH is its <strong>plugin interface</strong> — you can add features, integrations,
  new content types, admin tools, and more by shipping plugins.
</p>

<hr>

<h2>Key Features</h2>
<ul>
  <li><strong>Lightweight &amp; performant</strong> — minimal overhead and designed to run quickly on typical PHP hosting.</li>
  <li><strong>User management</strong> — manage accounts, permissions, and access to admin functionality.</li>
  <li><strong>Simple configuration</strong> — easy to set up for local development or production hosting.</li>
  <li><strong>Plugin system</strong> — extend DASH without modifying core code.</li>
  <li><strong>CMS &amp; page management</strong> — create and manage pages/content from the admin area.</li>
  <li><strong>Secure by design</strong> — built with sensible defaults and an architecture that encourages safe patterns.</li>
</ul>

<hr>

<h2>What DASH-2 Is Good For</h2>
<ul>
  <li>Personal and professional websites</li>
  <li>Small business sites</li>
  <li>School / education sites</li>
  <li>Content-heavy pages where you want admin control without a heavy CMS footprint</li>
  <li>Projects where you want a CMS <em>and</em> the ability to extend it cleanly via plugins</li>
</ul>

<hr>

<h2>Installation</h2>

<p>
  The exact setup flow can vary depending on your hosting environment, but the general process is:
</p>

<ol>
  <li>
    <strong>Clone the repository</strong>
    <pre><code>git clone https://github.com/jamiebalfour04/DASH-2.git
cd DASH-2</code></pre>
  </li>

  <li>
    <strong>Configure your environment</strong><br>
    Set up your database and core settings (see the configuration section below).
  </li>

  <li>
    <strong>Run the installer / setup</strong><br>
    Visit the site in your browser and follow the setup steps (if applicable to your build).
  </li>

  <li>
    <strong>Log in to the admin area</strong><br>
    Create/manage users and begin building your site.
  </li>
</ol>

<p>
  <em>Tip:</em> For local development you can often run a quick PHP server from the project root:
</p>

<pre><code>php -S localhost:8000</code></pre>

<hr>

<h2>Configuration</h2>

<p>
  DASH aims to keep configuration straightforward and readable. You’ll typically configure:
</p>

<ul>
  <li><strong>Database connection</strong> (host, database name, username, password)</li>
  <li><strong>Site settings</strong> (site title, base URL, etc.)</li>
  <li><strong>Environment options</strong> (development vs production behaviour)</li>
</ul>

<p>
  If you’re running this on shared hosting, you should be able to configure DASH without needing anything exotic —
  just standard PHP + database hosting.
</p>

<hr>

<h2>User Management</h2>

<p>
  DASH includes built-in user management so you can control who can access the admin area and what they can do.
  Typical capabilities include:
</p>

<ul>
  <li>Create and manage admin users</li>
  <li>Set permissions / roles (depending on your build)</li>
  <li>Limit access to sensitive parts of the system</li>
</ul>

<p>
  This is especially useful if you’re building sites for clients, schools, or teams where more than one person
  needs access — but not everyone should have full control.
</p>

<hr>

<h2>Plugin System</h2>

<p>
  DASH-2 supports a plugin interface so you can extend the CMS cleanly. Plugins are the recommended way to add
  functionality because they keep the core upgradeable and your features isolated.
</p>

<h2>Contributing</h2>

<p>
  Contributions are welcome. If you want to add a feature or fix a bug:
</p>

<ol>
  <li>Fork the repo</li>
  <li>Create a feature branch</li>
  <li>Make changes with clear commits</li>
  <li>Open a Pull Request with a description of what you changed and why</li>
</ol>

<p>
  If you’re building an extension, consider shipping it as a <strong>plugin</strong> rather than editing core.
</p>

<hr>

<h2>Licence</h2>

<p>
  This project is released under the <strong>GPL-3.0</strong>.
</p>
