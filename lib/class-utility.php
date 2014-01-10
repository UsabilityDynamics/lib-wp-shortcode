<?php
/**
 * Help class
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

  if( !class_exists( 'UsabilityDynamics\Shortcode\Utility' ) ) {

    class Utility {

      /**
       * Parses passed directory for shortcodes files
       * includes and adds shortcodes if they exist
       * 
       * @param $path
       * @author peshkov@UD
       */
      static public function maybe_load_shortcodes( $path ) {
        if ( !is_dir( $path ) ) {
          return null;
        }
        
        if ( $dir = opendir( $path ) ) {
          $headers = array(
            'name' => 'Name',
            'id' => 'ID',
            'type' => 'Type',
            'group' => 'Group',
            'class' => 'Class',
            'version' => 'Version',
            'description' => 'Description',
          );
          while ( false !== ( $file = readdir( $dir ) ) ) {
            $data = @get_file_data( $path . "/" . $file, $headers, 'shortcode' );
            //die($data[ 'class' ]);
            if( $data[ 'type' ] == 'shortcode' && !empty( $data[ 'class' ] ) ) {
              include_once( $path . "/" . $file );
              if( class_exists( $data[ 'class' ] ) ) {
                new $data[ 'class' ]();
              }
            }
            //echo "<pre>"; echo $data[ 'class' ]; var_dump( class_exists( $data[ 'class' ] ) ); echo "</pre>";
          }
        }
      }
    
    }

  }

}