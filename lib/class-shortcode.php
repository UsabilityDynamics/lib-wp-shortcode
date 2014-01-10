<?php
/**
 * Shortcode class
 * Declares shortcodes.
 *
 * @author potanin@UD
 * @author peshkov@UD
 * @author korotkov@UD
 *
 * @version 0.1.0
 * @package UsabilityDynamics
 * @subpackage Shortcode
 */
namespace UsabilityDynamics\Shortcode {

  if( !class_exists( 'UsabilityDynamics\Shortcode\Shortcode' ) ) {

    class Shortcode {
    
      /**
       * Unique identifier.
       * Can be set in constructor.
       *
       * @value string
       */
      var $id = '';
    
      /**
       * Shortcode params.
       * Can be set in constructor.
       * See param structure in self::_param_sync();
       *
       * @value array
       */
      var $params = array();
      
      /**
       * Shortcode description
       * Can be set in constructor.
       *
       * @value string
       */
      var $description = '';
      
      /**
       * Group
       * Example: 'WP-Property', 'WP-Invoice', etc
       * default is 'Default'.
       * Can be set in constructor.
       *
       * @value string
       */
      var $group = 'Default';
      
      /**
       * Constructor.
       * Inits shortcode and adds it to global variable $_shortcodes
       *
       */
      public function __construct( $options = array() ) {
        global $_shortcodes;
      
        if( !is_array( $_shortcodes ) ) {
          $_shortcodes = array();
        }
        
        // Set properties
        if( is_array( $options ) ) {
          foreach( $options as $k => $v ) {
            if( in_array( $k, array( 'id', 'params', 'description', 'group' ) ) ) {
              $this->{$k} = $v;
            }
          }
        }
        // All params must have the same structure
        if( is_array( $this->params ) ) {
          foreach( $this->params as $k => $val ) {
            $this->params[ $k ] = $this->_param_sync( $k, $val );
          }
        }
        // Add current shortcode to global variable
        $group = sanitize_key( $this->group );
        if( !isset( $_shortcodes[ $group ] ) || !is_array( $_shortcodes[ $group ] ) ) {
          $_shortcodes[ $group ] = array( 
            'name' => $this->group,
            'properties' => array(),
          );
        }
        $this->group = $group;
        array_push( $_shortcodes[ $group ][ 'properties' ], $this );
        
        // Now, we add shortcode to WP
        add_shortcode( $this->id, array( $this, 'call' ) );
        
        return $this;
      }
      
      /**
       * Must be rewritten by child class
       *
       */
      public function call( $params ) {
        return null;
      }
      
      /**
       * Param's schema
       *
       */
      private function _param_sync( $k, $v ) {
        $v = wp_parse_args( $v, array(
          'id' => $k,
          'name' => '',
          'description' => '',
          'is_multiple' => false,
          'type' => 'string', // boolean, string, number
          'enum' => array(),
          'default' => '', // default value description
        ) );
        return $v;
      }

    }

  }

}