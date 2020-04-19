<?php
/**
 * Contains general information about the site and its authors.
 *
 * PHP version used: 5.5.4
 * SQLite version used: 3.28.0
 *
 * Styling guide: PSR-12: Extended Coding Style
 *     (https://www.php-fig.org/psr/psr-12/)
 *
 * @category  PHPUserManager
 * @package   public
 * @author    Rob Garcia <rgarcia@rgprogramming.com>
 * @copyright 2019-2020 Rob Garcia
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/garciart/PHPUserManager
 */
/* Check if a session is already active */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* Start placing content into an output buffer */
ob_start();
?>
<!-- Head Content Start -->
<title>About Page | PHP User Manager</title>
<!-- Head Content End -->
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHead = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Body Content Start -->
<!-- Header Element Content -->
<h1 class="mt-3">PHP User Manager</h1>
<p class="lead">Host Application About Page</p>
<hr>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderHeader = ob_get_contents();
/* Clean out the buffer, but do not destroy the output buffer */
ob_clean();
?>
<!-- Main Element Content -->
<div class="text-center"><img class="mb-4" src="img/logo.png" alt="" width="72" height="72"></div>
<h2>This is the Host Application's About Page!</h2>
<p>This is an extension of my tutorial, <a href="https://github.com/garciart/MasterPagesInPHP" title="Master Pages in PHP" target="_blank">Master Pages in PHP</a>, incorporating a user administration system (using SQLite) and Bootstrap styling. You can download it and add it to your project, or you can clone it and use it as the foundation of your web site!</p>
<p>While my first programs were written in Basic and Assembly Language, the language I've used the most is C. To this day, if I have to write a quick utility program, I'll knock it out in C. So, it's not surprising that when I began to develop applications for the web, I started with C# and ASP.NET. All in all, I have written quite a few web sites using the lessons I learned from <a href="https://www.apress.com/us/book/9781590594681" title="Beginning ASP.NET 2.0 E-Commerce in C# 2005" rel="nofollow" target="_blank">Cristian Darie’s excellent book, Beginning ASP.NET 2.0 E-Commerce in C# 2005: From Novice to Professional</a>.</p>
<p>However, while I love .NET, it has its limitations. For example, once you reach a certain point, .NET is not free, and neither are Microsoft's development or production tools (e.g., Visual Studio, SQL Server, etc.). Another issue is that many companies do not provide .NET hosting services. Hopefully, .NET Core, the cross-platform successor to .NET, will take off. In the meantime, to better serve my clients, I began to create alternative versions of my boilerplates in other languages, such as PHP and JavaScript.</p>
<p>One of my boilerplates was a version of that beloved tool that many of us miss: The Web Site Administration Tool. Here it is for PHP, extending one of my previous tutorials, Master Pages in PHP. Feel free to copy it and use it if you need an authentication and user administration system. Have fun and good luck!</p>
<blockquote>
    <p><em><strong>To use, copy the UserManager folder into your project and add a link to the UserManager/LoginPage.php. You also have to change the default directories and settings in the UserManager/Config.php folder.</strong></em></p>
</blockquote>
<blockquote>
    <p><em><strong>Please note that the focus of this repo is the UserManager folder, which is meant to be copied, pasted, and integrated into a project through the Login page. For that reason, this repo does not attempt to apply a Symfony, Laravel, or Codeigniter folder structure.</strong></em></p>
</blockquote>
<hr>
<p>Thanks to Julen Pardo at <a href="https://www.webcodegeeks.com/php/login-form-php-example/" title="Julen Pardo at Web Code Geeks" rel="nofollow" target="_blank">Web Code Geeks</a> for the Key Derivation Functions (KDF).</p>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderMain = ob_get_contents();
/* Clean out the buffer once again, but do not destroy the output buffer */
ob_clean();
?>
<!-- Footer Element Content -->

<!-- Body Content End -->
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolderFooter = ob_get_contents();
/* Clean out the buffer and turn off output buffering */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
require_once "Master.php";
