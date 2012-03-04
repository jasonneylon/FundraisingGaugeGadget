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
      .num { font-weight: bold; font-size: 24px; }
      .apply {
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

      #bar {
        background: transparent url(<?php echo plugins_url( 'images/Thermometer.png', __FILE__ ); ?>) no-repeat 0 0;
        height: 150px;
        left: 15px;
        top: 15px;
        overflow: hidden;
        width: 150px;
        z-index: 110;
      }

      #bar span
      {
        background: transparent url(<?php echo plugins_url( 'images/Thermometer-overlay.png', __FILE__ ); ?>) no-repeat left bottom;
        background-position: left bottom!important;
        background-repeat: no-repeat;
        bottom: 29px;
        display: block;
        font-style: normal;
        height: 0;
        left: 0;
        line-height: 0;
        overflow: hidden;
        position: absolute;
        text-indent: -9999em;
        width: 150px;
      }

      #bar strong
      {
        color: white;
        font-size: 3em;
        left: 0;
        position: absolute;
        text-align: center;
        width: 122px;
        top: 45px;
        z-index: 102;
      }

      #gauge
      {
        position: relative;
        left: -10px;
        height: 150px;
        overflow: hidden;
        width: 150px;
        z-index: 100;
        margin-top: -10px;
        margin-bottom: 8px;
      }

    </style>
    <script type="text/javascript">
    jQuery(document).ready(function() {
       var barHeight = 150;

       var percentage = <?php echo $this->percentage_raised($instance); ?>;

       var raisedHeight = (barHeight * percentage) / 100;
       // alert(raisedHeight);
       var percentageText = percentage + "%";
       // alert(percentageText);


      options = {
        duration: 1500, 
        step: function(now) { 
          var currentPercentage = Math.floor((now / barHeight) * 100);
          jQuery("#bar em").text(currentPercentage + "%"); } 
      }

       jQuery("#bar span").animate({ height: raisedHeight}, options);
      // jQuery("#bar span").height(raisedHeight);
      // jQuery("#bar em").text(percentageText);
     });
    </script>
    <li class="widget" id="investmentgauge">
      <h2>
        <a href="#" rel="nofollow" class="sidebartitle">Investment raised</a>
      </h2> 
      <div class="figures">
        <div id="gauge" style="border: solid 1px red">
          <div id="bar" >
            <span></span>
            <strong>
              <em>
                <?php
                echo 0; //$this->percentage_raised($instance); 
                ?>%
              </em>
            </strong>
          </div>
        </div>
        <div>
          <span class="num">
          <?php
          echo $this->days_to_go($instance); 
          ?> 
        </div>
        <div>
          days to go
        </div>
        <div>
          <span class="num">
          <?php
          echo $this->money_raised($instance); 
          ?> 
        </div>
        <div class="totalneeded">pledged of our £75,000 goal</div>
        <div>
          <span class="num">
          <?php
          echo $this->investors($instance); 
          ?>
        </span>
         </div>
         <div>
          investors
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
