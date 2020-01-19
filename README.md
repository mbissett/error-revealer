# Error Revealer (a fork of wp-no-white-screen)

This plugin helps reveal errors that might not be visible when WP_DEBUG is enabled. Sometimes, simply having WP_DEBUG enabled does not reveal all of the errors that are taking place (whether it is a fatal error that is occurring, or a notice that just so happens to tie into the issue you are encountering).

When this is the case, this plugin is your friend. :)

(it is also your friend when your host tells you that no errors are visible on their end, and you need to try enabling WP_DEBUG on your site to find out the cause of the error)

## Usage

 - Download Error Revealer here: https://github.com/mbissett/error-revealer/archive/master.zip - and unzip the contents of the .zip file to a easy to find folder on your computer.
 - Using your FTP client (or your host's file manager), take the following steps:
   <ul>
    <li>Open the `/mu-plugins` folder in the `/wp-content` folder for your WordPress installation (the `/wp-content` folder is usually in your `/public_html` folder). If the `/mu-plugins` folder does not exist yet, please create it & then open it.</li>

    <li>Upload `error-revealer.php` to the `/wp-content/mu-plugins` folder.</li>

    <li>Open the `wp-config.php` file in the root folder for your WordPress installation, and add the lines below to this file (if these lines are already present, there is no need to add them again):
    <br><br>    
    <div style="margin: 0 40px;"><pre>
    // Enable WordPress's debug mode
    define('WP_DEBUG', true);

    // Necessary for writing errors to /wp-content/debug.log
    define('WP_DEBUG_LOG', true);

    // Hide errors on the front end
    define('WP_DEBUG_DISPLAY', false);
    @ini_set('display_errors', 0);
    </pre></div>
    _NOTE: Insert these lines **above** the /* That's all, stop editing! Happy blogging. */ line in your wp-config.php file!_</li>
  </ul>
 
 - Now, take the steps to reproduce the error. Error Revealer will write entries to `/wp-content/debug.log` as information is available.
 

**IMPORTANT**: *Remember to remove this plugin after debugging the error!* Leaving it in the /mu-plugins folder on your site will have a negative impact on your site's performance.
 
## History

The original plugin, wp-no-white-screen, was meant to help reveal errors on the WSOD (White Screen of Death) page that can sometimes occur when using WordPress.

As I experimented with wp-no-white-screen while handling support requests, though, I also found the plugin to be very useful as a quick debug tool when helping customers, as I could enable WP_DEBUG on a site, upload the plugin, get some error information that otherwise wasn't usually visible, and find out what was causing the problem (when previously, the cause wasn't apparent).

Since I also found it useful in cases where asking the host to check the server's error logs didn't go well (either the host wasn't able to check them, or no entries were found), I decided to fork the plugin, and make it into something easily usable by customers as well.

In this fork, I've hidden the output from the frontend (whereas wp-no-white-screen displayed errors in the front end), so that the output is only being written to /wp-content/debug.log. Not only does this mean that your site visitors won't see any debug messages while you're recreating the issue, it also means that extra issues won't pop up (due to the fact that outputting debug messages in some places can break things).

## Further Reading 

 * [The WordPress Codex](http://codex.wordpress.org/Debugging_in_WordPress)
 * [Other Cool Options](http://nacin.com/2010/04/23/5-ways-to-debug-wordpress/)
