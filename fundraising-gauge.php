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
    $percentage = intval($money_raised / 75000 * 100);
    return $percentage;
  }

  public function money_raised($instance) {
    $money_raised = intval($instance["money_raised"]);
    return "£" . number_format ($money_raised);
  }

  public function investors($instance) {
    return intval($instance["investors"]);;
  }

  function days_to_go($instance) {
    date_default_timezone_set('Europe/London');
    $startDate = time();
    $endDate = strtotime('2012-03-10');
    if ($endDate < $startDate)
    {
      return 0;
    }
    $numDays = intval(date('d', $endDate - $startDate));
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
        font-size: 1.8em;
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
       var barHeight = 190;
       var percentage = <?php echo $this->percentage_raised($instance); ?>;
       var raisedHeight = (barHeight * percentage) / 100;
       var percentageText = percentage + "%";

      options = {
        duration: 1500, 
        step: function(now) { 
          var currentPercentage = Math.floor((now / barHeight) * 100);
          jQuery("#bar em").text(currentPercentage + "%"); } 
      }

       jQuery("#bar span").animate({ height: raisedHeight}, options);
     });
    </script>
    <li class="widget" id="investmentgauge">
      <h2>
        <a href="#" rel="nofollow" class="sidebartitle">Investment raised</a>
      </h2> 
      <div class="figures">
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
          <div class="metric">
            <span class="num">
            <?php
              $days = $this->days_to_go($instance);
              echo (($days == 0) ? "Last" : $days); 
            ?> 
          </div>
          <div>
            <?php
              $days = $this->days_to_go($instance);
              echo (($days == 0) ? "day" : "days to go"); 
            ?> 
          </div>
          <div class="metric">
            <span class="num">
            <?php
            echo $this->money_raised($instance); 
            ?> 
          </div>
          <div class="totalneeded">pledged of our<br/> £75,000 goal</div>
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
        <div class="apply"><a href="https://brixtonenergy.co.uk/shareoffer.php">Invest now</a></div>
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
