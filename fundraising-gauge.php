<?php
/*
Plugin Name: Fundraising gauge
Plugin URI: http://jasonneylon.wordpress.com
Description: Show how much money you have raised 
Author: Jason
Version: 1.1
Author URI: http://jasonneylon.wordpress.com
*/

class Fundraising_Gauge_Widget extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'fundraising_gauge_widget', // Base ID
      'Fundraising Gauge', // Name
      array( 'description' => __( 'Fundraising Gauge', 'text_domain' ), ) // Args
    );
  }

  public function percentage_raised($instance) {
    $money_raised = intval($instance["money_raised"]);
    $percentage = intval($money_raised / $this->target($instance) * 100);
    return $percentage;
  }

  public function target($instance) {
    return intval($instance["target"]);;
  }

  public function formattedTarget($instance) {
    $target = $this->target($instance);
    return "£" . number_format ($target);
  }

  public function money_raised($instance) {
    $money_raised = intval($instance["money_raised"]);
    return "£" . number_format ($money_raised);
  }

  public function investors($instance) {
    return intval($instance["investors"]);;
  }

  function last_day($instance) {
    $days = $this->days_to_go($instance);
    return ($days == 0);
  }

  function day_text($instance) {
    $days = $this->days_to_go($instance);
    switch ($days) {
      case 0:
        return "day"; 
        break;
      case 1:
        return "day to go";
        break;
      default:
        return "days to go";
        break;
    }
  }

  function before_deadline($instance) {
    return ($this->today() <= $this->deadline($instance));
  }

  function deadline($instance) {
    date_default_timezone_set('Europe/London');
    return new DateTime($instance["deadline"]);
  }

  function today() {
    date_default_timezone_set('Europe/London');
    $today = new DateTime();
    return new DateTime($today->format('Y-m-d'));
  }

  function date_diff($d1, $d2){
      $d1 = (is_string($d1) ? strtotime($d1) : $d1);
      $d2 = (is_string($d2) ? strtotime($d2) : $d2);  
      $diff_secs = abs($d1 - $d2);
      return floor($diff_secs / (3600 * 24));
  }

  function days_to_go($instance) {
    date_default_timezone_set('Europe/London');
    $endDate = $this->deadline($instance);;
    $today = $this->today();
    $numDays = $this->date_diff($today->format('Y-m-d'), $endDate->format('Y-m-d'));
    return $numDays;
  }

  public function widget( $args, $instance ) {
    extract($args, EXTR_SKIP);
    $title = apply_filters( 'widget_title', "Investment raised");
    ?>
    <style>
      .figures { padding: 6px 26px 15px 26px ;} 
      .num { font-weight: bold; font-size: 24px; padding-top: 20px; }
      .apply {
        clear: both;
        background: url(<?php echo plugins_url( 'images/back-submit.png', __FILE__ ); ?>) no-repeat;
        text-align: center;
        width: 220px;
        height: 34px;
        line-height: 34px;
        border: none;
        cursor: pointer;
        margin-top: 10px;
      }

      .closed {
        clear: both;
        background: #66df4d;
        color: black;
        text-align: center;
        width: 220px;
        height: 34px;
        line-height: 34px;
        border: none;
        margin-bottom: 20px;
        font-size: 16px;
        font-weight: bold;
      }

      .finished { font-size: 16px; padding-top: 20px;}

      #investmentgauge .apply a {
        color: white;
        font-size: 18px;
      }

      #investmentgauge .apply a:link {color: white;}
      #investmentgauge .apply a:visited {color: white;}
      #investmentgauge .apply a:hover {color: white;}
      #investmentgauge .apply a:focus {color: white;}
      #investmentgauge .apply a:active {color: white;}

      #gauge
      {
        position: relative;
        left: 0px;
        height: 187px;
        overflow: hidden;
        width: 100px;
        z-index: 100;
        margin-top: 5px;
        margin-bottom: 8px;
        float: left;
      }

      #bar {
        background: transparent url(<?php echo plugins_url( 'images/battery.png', __FILE__ ); ?>) no-repeat 0 0;
        height: 190px;
        left: 15px;
        top: 15px;
        overflow: hidden;
        width: 80px;
        z-index: 110;
      }

      #bar span
      {
        background: transparent url(<?php echo plugins_url( 'images/battery-overlay.png', __FILE__ ); ?>) no-repeat left bottom;
        background-position: left bottom!important;
        background-repeat: no-repeat;
        bottom: 0px;
        display: block;
        font-style: normal;
        height: 0;
        left: 0;
        line-height: 0;
        overflow: hidden;
        position: absolute;
        text-indent: -9999em;
        width: 110px;
      }

      #bar strong
      {
        color: black;
        font-size: 1.7em;
        left: 0;
        position: absolute;
        text-align: center;
        width: 70px;
        top: 80px;
        z-index: 102;
      }

      .metric 
      {
        padding-top: 8px;
      }
    </style>
    <script type="text/javascript">
    jQuery(document).ready(function() {
       var barHeight = 138;
       var paddingBottom = 23;
       var percentage = <?php echo $this->percentage_raised($instance); ?>;
       var raisedHeight = ((barHeight * percentage) / 100) + paddingBottom;
       var percentageText = percentage + "%";

      options = {
        duration: 1500, 
        step: function(now) { 
          var currentPercentage = Math.floor(((now - paddingBottom) / barHeight) * 100);
          if (currentPercentage < 0) {
            currentPercentage = 0;
          }
          jQuery("#bar em").text(currentPercentage + "%"); } 
      }
       jQuery("#bar span").animate({ height: raisedHeight }, options);
     });
    </script>

    <?php
      echo $before_widget;
      if ( ! empty( $title ) )
        echo $before_title . $title . $after_title;
    ?>

      <div class="figures" id="investmentgauge">
<!--         <?php 
          if ($instance['closed'] = 'on') {
        ?> 
           <div class="closed">Thanks to all our investors!</div>
        <?php
          }
        ?>
 -->        <div id="gauge">
          <div id="bar" >
            <span></span>
            <strong>
              <em>
                <?php
                $this->percentage_raised($instance); 
                ?>%
              </em>
            </strong>
          </div>
        </div>
        <div class="numbers">
          <?php if ($this->before_deadline($instance)) { ?>
            <div class="metric">
              <span class="num">
              <?php
                echo ($this->last_day($instance) ? "Last" : $this->days_to_go($instance)); 
              ?>
              </span> 
            </div>
            <div>
              <?php
                echo ($this->day_text($instance)); 
              ?> 
           </div>
          <?php } ?>
          <div class="metric">
            <span class="num">
            <?php
            echo $this->money_raised($instance); 
            ?> 
            </span>
          </div>
          <div class="totalneeded">raised of our<br/> 
          <?php echo ($this->formattedTarget($instance)); ?> goal</div>
          <div class="metric">
            <span class="num">
            <?php
            echo $this->investors($instance); 
            ?>
            </span>
           </div>
           <div>
            investors
          </div>
        </div>
          <div class="apply"><a href="<?php echo $instance['button_link'] ?>"><?php echo $instance['button_text'] ?></a></div>
      </div>
  <?php
      echo $after_widget;
  }

  public function form( $instance ) {
    $instance = wp_parse_args( (array) $instance, array( 'closed' => '', 'money_raised' => 0, 'investors' => 0, "deadline" => "2014-12-31",  'target' => 10000, 'button_text' => "Invest now", 'button_link' => '/invest/shareoffer2/' ));
    $money_raised = $instance['money_raised'] ;
    $investors = $instance['investors'] ;
    $deadline = $instance['deadline'];
    $target = $instance['target'];
    $button_text = $instance['button_text'];
    $button_link = $instance['button_link'];
    
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'money_raised' ); ?>"><?php _e( 'Money raised:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'money_raised' ); ?>" name="<?php echo $this->get_field_name( 'money_raised' ); ?>" type="number" step="1" min="0" value="<?php echo $money_raised; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'investors' ); ?>"><?php _e( 'Investors:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'investors' ); ?>" name="<?php echo $this->get_field_name( 'investors' ); ?>" type="number" step="1" min="0" value="<?php echo $investors; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e( 'Target:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" type="number" step="1" min="0" value="<?php echo $target; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'deadline' ); ?>"><?php _e( 'Deadline:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'deadline' ); ?>" name="<?php echo $this->get_field_name( 'deadline' ); ?>" type="date" step="1" min="0" value="<?php echo $deadline; ?>" />
    </p>
    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['closed'], "on") ?> id="<?php echo $this->get_field_id('closed'); ?>" name="<?php echo $this->get_field_name('closed'); ?>" />
      <label for="<?php echo $this->get_field_id('closed'); ?>"><?php _e('Close fundraising'); ?></label><br />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button text:'); ?> 
      <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" /></label>
    </p>    
    <p>
      <label for="<?php echo $this->get_field_id('button_link'); ?>"><?php _e('Button link:'); ?> 
      <input class="widefat" id="<?php echo $this->get_field_id('button_link'); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_attr($button_link); ?>" /></label>
    </p>    
    <?php 
  }

}

add_action( 'widgets_init', create_function( '', 'register_widget( "fundraising_gauge_widget" );' ) );