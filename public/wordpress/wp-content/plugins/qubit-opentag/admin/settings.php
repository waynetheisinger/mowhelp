<?php

add_action('admin_head', 'opentag_admin_add_css');
function opentag_admin_add_css () {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . opentag_base_dir() . '/css/admin.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}

add_action('admin_menu', 'opentag_add_admin_options');
function opentag_add_admin_options() {
  add_menu_page(
    'QuBit OpenTag',
    'QuBit OpenTag',
    'manage_options',
    __FILE__,
    'opentag_initialize_options',
    plugin_dir_url( __FILE__ )."../images/menu-icon.png"
  );
}

function opentag_get_status() {
  if ( !opentag_is_enabled() ) {
    return array(
      'running' => false,
      'message' => 'OpenTag Plugin is Disabled'
    );
  }

  if (get_option('opentag_container_id') == '') {
    return array(
      'running' => false,
      'message' => 'Container ID is Empty'
    );
  }

  return array(
    'running' => true,
    'message' => opentag_get_script()
  );
}

function opentag_initialize_options() {
  if (!get_option('opentag_disabled')) {
    add_option('opentag_disabled', 'false');
  }

  if (!get_option('opentag_loading_method')) {
    add_option('opentag_loading_method', 'async');
  }

  opentag_load_admin_content();
}

function opentag_load_admin_content() {
  if (isset($_POST['opentag_configuration_options'])) {
    $options = array(
      'opentag_container_id',
      'opentag_loading_method',
      'opentag_disabled'
    );
    foreach($options as $i => $key) { 
      if (isset($_POST[$key])) { 
        update_option($key, $_POST[$key]);
      }
    }
  }
  ?>
  <div class="wrap qubit-opentag">
    <h2 class="logo"></h2>

    <div class="tips">
      <ul>
        <li>
          <a href="http://www.opentag.qubitproducts.com/about/" target="_blank">About OpenTag</a>
        </li>
        <li>
          <a href="http://www.opentag.qubitproducts.com/about/events/" target="_blank">Upcoming Events</a>
        </li>
        <li>
          <a href="http://www.opentag.qubitproducts.com/help-center/how-to-videos/" target="_blank">How to Videos</a>
        </li>
        <li>
          <a href="http://opentagsupport.qubitproducts.com/help/" target="_blank">Knowledge Center</a>
        </li>
        <li>
          <a href="https://github.com/QubitProducts/OpenTag" target="_blank">Fork on GitHub</a>
        </li>
      </ul>
    </div>

    <div class="about">
      <p>
        QuBit OpenTag is an open Tag Management platform for developing, deploying and managing JavaScript or other HTML
        Tags without touching your website templates. OpenTag is FREE for small sites with less than one million page views
        per month, no credit card details required. </p>
      <p>
        This WordPress plugin automatically adds your configured container tag to every page.
      </p>

      <p>
      If you haven't registered, you should <a href="https://opentag.qubitproducts.com/QDashboard/register.html" target="_blank" class="signup"> Signup for FREE!</a>
      
      You can <a href="https://opentag.qubitproducts.com/QDashboard/" target="_blank" class="login">Sign In</a> to obtain container ID.
      </p>
    </div>

    <div class="message-container">
    <?php
      $status  = opentag_get_status();
      $running = $status['running']; 
      $msg     = $status['message'];
      $statusMsg = $status['running'] ? 'Enabled': 'Disabled';
    ?>
      <div class="running <?php echo strtolower($statusMsg) ?>">
        <div class="status">
          <strong>Status:</strong> <?php echo $statusMsg ?>
        </div>
        <div class="message">
          <?php if ($running == false) { ?>
          <strong>Reason:</strong> <?php echo $msg ?>
          <?php } else { ?>          
          <strong>Current OpenTag Script:</strong> <br/>
          <pre>
<?php echo htmlspecialchars(opentag_get_script()) ?>
          </pre>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="clear"></div>
    <form method="post">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th>
            <label for="opentag_container_id">OpenTag Container ID</label>
          </th>
          <td>
            <input type="text" name="opentag_container_id" value="<?php echo get_option('opentag_container_id'); ?>"/></br>
            <span class="helper">
              Click 'Embed' button in OpenTag interface for the container you'd like to implement. <br/>
              Your container ID should look like 1234-56789, for example: <br/>
              &lt;script src="//d3c3cq33003psk.cloudfront.net/opentag-<strong>1234-56789</strong>.js" async defer&gt;&lt;/script&gt;
            </span>
          </td>
        </tr>
        
        <tr valign="top">
          <th>
            <label for="sync">Loading Method</label>
          </th>
          <td>
            <?php $loadOption = get_option('opentag_loading_method'); ?>
            <input type="radio" name="opentag_loading_method" value="async" <?php checked('async' == $loadOption)?> /> Asynchronous
            <span class="option-gap"></span>
            <input type="radio" name="opentag_loading_method" value="sync" <?php checked('sync' == $loadOption)?> /> Synchronous<br/>
            <span class="helper">Asynchronous is recommended.</span>
          </td>
        </tr>
        
        <tr valign="top">
          <th>
            <label for="disable">OpenTag Script</label>
          </th>
          <td>
            <?php $disabled = get_option('opentag_disabled') ?>
            <input type="radio" name="opentag_disabled" value="false" <?php checked($disabled == 'false') ?> /> Enable
            <span class="option-gap"></span>
            <input type="radio" name="opentag_disabled" value= "true" <?php checked($disabled == 'true') ?> /> Disable<br/>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit">
      <input type="hidden" name="opentag_configuration_options" value="1"/>
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
    </form>

  </div>
<?php
  }
?>