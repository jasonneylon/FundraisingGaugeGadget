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
  $money_raised = intval(get_option("fg_money_raised"));
  $percentage = intval($money_raised / 75000 * 100);
  return $percentage . "%";
}

function money_raised() {
  $money_raised = intval(get_option("fg_money_raised"));
  return "£" . number_format ($money_raised);
}

function investors() {
  return intval(get_option("fg_investors"));;
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