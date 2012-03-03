<?php
/*
Plugin Name: Fundraising gauge
Plugin URI: http://jasonneylon.wordpress.com
Description: Show how much money you have raised 
Author: Jason
Version: 1
Author URI: http://jasonneylon.wordpress.com
*/
 
function percentage_raised() {
  return "60%";
}

function money_raised() {
  return "£" . number_format (30000);
}

function investors() {
  return "50";
}

function show_gauge() {
?>
  <div>
    <h2>Deadline: March 10</h2> 
    <div id="gauge">
      <span>
      <?php
      echo percentage_raised(); 
      ?>
      </span>
    </div>
    <div class="raised">
      <?php
      echo money_raised(); 
      ?>
    </div>
    <div class="totalneeded">of £75,000 raised</div>
    <div class="investors">
      <?php
      echo investors(); 
      ?>
     investors</div>
    <div class="apply"><a href="https://brixtonenergy.co.uk/shareoffer.php">Apply now</a></div>
  </div>
<?php
}
 
 function widget_fundraising_gauge() {
?>
  <?php show_gauge(); ?>
<?php
}
 
function gauge_init()
{
  register_sidebar_widget(__('Fundraising Gauge'), 'widget_fundraising_gauge');
}
add_action("plugins_loaded", "gauge_init");