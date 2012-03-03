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
      'Fundraising Gauge Widget', // Name
      array( 'description' => __( 'Fundraising Gauge', 'text_domain' ), ) // Args
    );
  }


  public function percentage_raised($instance) {
    $money_raised = intval($instance["money_raised"]);
    $percentage = intval($money_raised / 75000 * 100);
    return $percentage . "%";
  }

  public function money_raised($instance) {
    $money_raised = intval($instance["money_raised"]);
    return "£" . number_format ($money_raised);
  }

  public function investors($instance) {
    return intval($instance["investors"]);;
  }

  public function widget( $args, $instance ) {
  ?>
    <div>
      <h2>Deadline: March 10</h2> 
      <div id="gauge">
        <span>
        <?php
        echo $this->percentage_raised($instance); 
        ?>
        </span>
      </div>
      <div class="raised">
        <?php
        echo $this->money_raised($instance); 
        ?>
      </div>
      <div class="totalneeded">of £75,000 raised</div>
      <div class="investors">
        <?php
        echo $this->investors($instance); 
        ?>
       investors</div>
      <div class="apply"><a href="https://brixtonenergy.co.uk/shareoffer.php">Apply now</a></div>
    </div>
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
