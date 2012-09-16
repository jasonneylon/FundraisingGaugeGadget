<?php
/*
Plugin Name: Fundraising gauge
Plugin URI: http://jasonneylon.wordpress.com
Description: Show how much money you have raised 
Author: Jason
Version: 1
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
    $percentage = intval($money_raised / $this->target() * 100);
    return $percentage;
  }

  public function target() {
    return 61500;
  }

  public function formattedTarget() {
    $target = $this->target();
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

  function open($instance) {
    return ($this->today() <= $this->deadline());
  }

  function deadline() {
    date_default_timezone_set('Europe/London');
    return new DateTime('2012-10-12');
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
    $endDate = $this->deadline();;
    $today = $this->today();
    echo("<!--");
    var_dump($today);
    var_dump($endDate);
    echo($today->format('Y-m-d'));
    echo("-->");
    //$numDays = $today->diff($endDate)->days;
    //$numDays = 34;
    $numDays = $this->date_diff($today->format('Y-m-d'), $endDate->format('Y-m-d'));
    return $numDays;
  }

  public function widget( $args, $instance ) {
  ?>
    <style>
      .figures { padding: 6px 0 15px 26px ;} 
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

      .contact {
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

      #investmentgauge .contact a {
        color: white;
        font-size: 14px;
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

      #investmentgauge .contact a:link {color: white;}
      #investmentgauge .contact a:visited {color: white;}
      #investmentgauge .contact a:hover {color: white;}
      #investmentgauge .contact a:focus {color: white;}
      #investmentgauge .contact a:active {color: white;}

      #gauge
      {
        position: relative;
        left: 0px;
        height: 190px;
        overflow: hidden;
        width: 100px;
        z-index: 100;
        margin-top: -10px;
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
       var barHeight = 135;
       var paddingBottom = 25;
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
    <li class="widget" id="investmentgauge">
      <h2>
        <a href="#" rel="nofollow" class="sidebartitle">Investment raised</a>
      </h2> 
      <div class="figures">
        <?php 
          if (!$this->open($instance)) {
        ?> 
           <div class="closed">Thanks to all our investors!</div>
        <?php
          }
        ?>
        <div id="gauge">
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
          <?php if ($this->open($instance)) { ?>
            <div class="metric">
              <span class="num">
              <?php
                echo ($this->last_day($instance) ? "Last" : $this->days_to_go($instance)); 
              ?> 
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
          </div>
          <div class="totalneeded">raised of our<br/> 
          <?php echo ($this->formattedTarget()); ?> goal</div>
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
          <?php if (!$this->open($instance)) { ?>
            <div class="metric">
              <span class="finished">
                Our share offer closed on <br/>
                <strong><?php echo($this->deadline()->format("d/m/Y")); ?></strong>
              </span>
           </div>
          <?php } ?>
        </div>
        <?php 
          if ($this->open($instance)) {
        ?> 
          <div class="apply"><a href="https://brixtonenergy.co.uk/invest/shareoffer2/">Invest now</a></div>
        <?php 
          }
          else {
        ?>
          <div class="contact"><a href="https://brixtonenergy.co.uk/contact-2/">Contact me about the next offer</a></div>
        <?php
          }
        ?> 
      </div>
    <li>
  <?php
  }

  public function form( $instance ) {
    if ( $instance ) {
      $money_raised = $instance[ 'money_raised' ] ;
      $investors = $instance[ 'investors' ] ;
    }
    else {
      $money_raised = 0;
      $investors = 0;
    }

    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'money_raised' ); ?>"><?php _e( 'Money raised:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'money_raised' ); ?>" name="<?php echo $this->get_field_name( 'money_raised' ); ?>" type="text" value="<?php echo $money_raised; ?>" />
    </p>
    <p>
    <label for="<?php echo $this->get_field_id( 'investors' ); ?>"><?php _e( 'Investors:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'investors' ); ?>" name="<?php echo $this->get_field_name( 'investors' ); ?>" type="text" value="<?php echo $investors; ?>" />
    </p>
    <?php 
  }

}

add_action( 'widgets_init', create_function( '', 'register_widget( "fundraising_gauge_widget" );' ) );