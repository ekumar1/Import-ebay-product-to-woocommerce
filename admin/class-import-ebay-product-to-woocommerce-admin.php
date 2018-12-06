<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ekumar.com.np
 * @since      1.0.0
 *
 * @package    Import_Ebay_Product_To_Woocommerce
 * @subpackage Import_Ebay_Product_To_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Import_Ebay_Product_To_Woocommerce
 * @subpackage Import_Ebay_Product_To_Woocommerce/admin
 * @author     E Kumar Shrestha <shresthaekumar@gmail.com>
 */
class Import_Ebay_Product_To_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Import_Ebay_Product_To_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Import_Ebay_Product_To_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/import-ebay-product-to-woocommerce-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('bootstrap.min.css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css", array(), $this->version, 'all' );
		
		

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Import_Ebay_Product_To_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Import_Ebay_Product_To_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script("jquery.min.js", plugin_dir_url( __FILE__ ) . 'js/jquery.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script("custom.js", plugin_dir_url( __FILE__ ) . 'js/custom.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/import-ebay-product-to-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
	 	wp_localize_script($this->plugin_name, 'ebay_ajax',admin_url('admin-ajax.php'));

	}
	
	
        
        // action function for above hook
        function import_ebay_product_to_woocommerce_menu()
        {
            // Add a new top-level menu (ill-advised):
        add_menu_page(__('Find eBay Products','menu-test'), __('Import Ebay to Woocommerce','menu-test'), 'manage_options', 'find_ebay_products', array($this,'find_ebay_products_function') );
          
        }
         
        // mt_toplevel_page() displays the page content for the custom Test Toplevel menu
        function find_ebay_products_function()
        {
         
          include plugin_dir_path( __FILE__ ) . '../admin/partials/find_ebay_products.php';
           
        }
      // mt_toplevel_page() displays the page content for the custom Test Toplevel menu
        function get_ebay()
        {
            echo "--";
            
           if(isset($_GET['ebay_id']))
                {
                  $this->insert_product($this->global_get_ebay_item_details($_GET['ebay_id']));
                }
        }
        
        function insert_product ($product_data)  
        {
             
             if (array_key_exists("Variations",$product_data->Item))
              {
                
                echo "This is Variation Product ";
                
                  // create variable products
                  
              //   create_variable_products($product_data);
              }
            else
              {
				  
				   echo "This is Simple Product ";
				   
                  // create simple products
              //  create_simple_products($product_data);
              }
          
          
        }

         // create variable products
         
        function create_variable_products($product_data)
        {
              
              
             $post = array( // Set up the basic post data to insert for our product
        
                'post_author'  => 1,
                'post_content' => $product_data->Item->Description,
                'post_status'  => 'publish',
                'post_title'   => $product_data->Item->Title,
                'post_parent'  => '',
                'post_type'    => 'product'
            );
        
            $post_id = wp_insert_post($post); // Insert the post returning the new post id
        
            if (!$post_id) // If there is no post id something has gone wrong so don't proceed
            {
                return false;
            }
        
        
               uploadMedia($post_id,$product_data->Item->PictureURL);
            
            
                wp_set_object_terms( $post_id, (string) $product_data->Item->PrimaryCategoryName, 'product_cat' );
                wp_set_object_terms($post_id, 'variable', 'product_type');
                update_post_meta( $post_id, '_visibility', 'visible' );
                
                  
                    foreach ($product_data->Item->Variations->Variation as $variants)
                    {
                        
                       create_product_variation( $post_id, $variants );
                      
                    }
            
          
        }
        
          // create simple products
        
        function create_simple_products($product_data)
        {
        
        echo "<pre>";
        //print_r($product_data);
        
         
         
             $post = array( // Set up the basic post data to insert for our product
        
                'post_author'  => 1,
                'post_content' => $product_data->Item->Description,
                'post_status'  => 'publish',
                'post_title'   => $product_data->Item->Title,
                'post_parent'  => '',
                'post_type'    => 'product'
            );
        
            $post_id = wp_insert_post($post); // Insert the post returning the new post id
        
            if (!$post_id) // If there is no post id something has gone wrong so don't proceed
            {
                return false;
            }
        
        
               uploadMedia($post_id,$product_data->Item->PictureURL);
            
                wp_set_object_terms( $post_id, (string) $product_data->Item->PrimaryCategoryName, 'product_cat' );
                wp_set_object_terms($post_id, 'simple', 'product_type');
        
                update_post_meta( $post_id, '_visibility', 'visible' );
                update_post_meta( $post_id, '_stock_status', 'instock');
                update_post_meta( $post_id, 'total_sales', '0'); 
                update_post_meta( $post_id, '_regular_price', (string) $product_data->Item->DiscountPriceInfo->OriginalRetailPrice ); 
                update_post_meta( $post_id, '_purchase_note', "" );
                update_post_meta( $post_id, '_featured', "no" );
                update_post_meta( $post_id, '_weight', "" );
                update_post_meta( $post_id, '_length', "" );
                update_post_meta( $post_id, '_width', "" );
                update_post_meta( $post_id, '_height', "" );
                update_post_meta($post_id, '_sku', (string) $product_data->Item->SKU); 
                update_post_meta( $post_id, '_sale_price',(string) $product_data->Item->CurrentPrice );
                update_post_meta( $post_id, '_sold_individually', "" );
                update_post_meta( $post_id, '_manage_stock', "yes" );
                update_post_meta( $post_id, '_backorders', "no" );
                update_post_meta( $post_id, '_stock', (string) $product_data->Item->Quantity );
             
         
                
                    foreach ($product_data->Item->ItemSpecifics->NameValueList as $attribute)
                    {
                        
                       add_product_attribute($post_id,(string) $attribute->Name,(string) $attribute->Value);
                    }
                    
                    
           
          
         
           }
 


 
 
         //print_r( wc_get_product(43332 ));
        
           
           
        // add product attribute
        
        function add_product_attribute($product_id,$name,$value)
        {
            
            
              $term_taxonomy_ids = wp_set_object_terms($product_id, $value, 'pa_'.$name, true );
             $thedata = Array('pa_'.$name=>Array(
               'name'=>'pa_'.$name,
               'value'=>$value,
               'is_visible' => '1',
               'is_taxonomy' => '1'
             ));
             
             update_post_meta($product_id,'_product_attributes',$thedata);  
         
        }
        
        
        
        /**
         * Create a product variation for a defined variable product ID.
         *
         * @since 3.0.0
         * @param int   $product_id | Post ID of the product parent variable product.
         * @param array $variation_data | The data to insert in the product.
         */
        
        function create_product_variation( $product_id, $variation_data )
        {
            // Get the Variable product object (parent)
            $product = wc_get_product($product_id);
        
            $variation_post = array(
                'post_title'  => $product->get_title(),
                'post_name'   => 'product-'.$product_id.'-variation',
                'post_status' => 'publish',
                'post_parent' => $product_id,
                'post_type'   => 'product_variation',
                'guid'        => $product->get_permalink()
            );
        
            // Creating the product variation
            $variation_id = wp_insert_post( $variation_post );
        
            // Get an instance of the WC_Product_Variation object
            $variation = new WC_Product_Variation( $variation_id );
        
           
           /*
                $taxonomy = 'pa_'.$attribute; // The attribute taxonomy
        
                // If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
                if( ! taxonomy_exists( $taxonomy ) )
                
                {
                    register_taxonomy(
                        $taxonomy,
                       'product_variation',
                        array(
                            'hierarchical' => false,
                            'label' => ucfirst( $taxonomy ),
                            'query_var' => true,
                            'rewrite' => array( 'slug' => '$taxonomy'), // The base slug
                          )
                        );
                 }
        
                // Check if the Term name exist and if not we create it.
                if( ! term_exists( $term_name, $taxonomy ) )
                    wp_insert_term( $term_name, $taxonomy ); // Create the term
        
                $term_slug = get_term_by('name', $term_name, $taxonomy )->slug; // Get the term slug
        
                // Get the post Terms names from the parent variable product.
                $post_term_names =  wp_get_post_terms( $product_id, $taxonomy, array('fields' => 'names') );
        
                // Check if the post term exist and if not we set it in the parent variable product.
                if( ! in_array( $term_name, $post_term_names ) )
                    wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
        
                // Set/save the attribute data in the product variation
                update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
           
            ## Set/save all other data
            */
            
            
            /*
            // SKU
            if( ! empty( $variation_data['sku'] ) )
                $variation->set_sku( $variation_data['sku'] );
            
            */
            
            
            /*
            // Prices
            if( empty( $variation_data['sale_price'] ) ){
                $variation->set_price( $variation_data->StartPrice );
            } else {
                $variation->set_price( $variation_data->StartPrice);
                //$variation->set_sale_price( $variation_data['sale_price'] );
            }
            */
            
            
            $variation->set_regular_price( (string) $variation_data->StartPrice );
        
            // Stock
            if( ! empty((string) $variation_data->Quantity) ){
                $variation->set_stock_quantity( (string) $variation_data->Quantity);
                $variation->set_manage_stock(true);
                $variation->set_stock_status('');
            } else {
                $variation->set_manage_stock(false);
            }
        
            $variation->set_weight(''); // weight (reseting)
        
            $variation->save(); // Save the data
        }
        

        // insert image
        function uploadMedia($post_id,$image_url){
            
        // Add Featured Image to Post
            
            $image_name       = 'wp-header-logo.png';
            $upload_dir       = wp_upload_dir(); // Set upload folder
            $image_data       = file_get_contents($image_url); // Get image data
            $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
            $filename         = basename( $unique_file_name ); // Create image file name
        
            // Check folder permission and define file location
            if( wp_mkdir_p( $upload_dir['path'] ) ) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }
        
            // Create the image  file on the server
            file_put_contents( $file, $image_data );
        
            // Check image file type
            $wp_filetype = wp_check_filetype( $filename, null );
        
            // Set attachment data
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => sanitize_file_name( $filename ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
        
            // Create the attachment
            $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        
            // Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        
            // Define attachment metadata
            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        
            // Assign metadata to attachment
            wp_update_attachment_metadata( $attach_id, $attach_data );
        
            // And finally assign featured image to post
            set_post_thumbnail( $post_id, $attach_id );
        }

 




      function global_get_ebay_item_details($item_id) {
     
     
             
            $request = '<?xml version="1.0" encoding="utf-8"?>
                        <GetSingleItemRequest xmlns="urn:ebay:apis:eBLBaseComponents" >
                        <ItemID>'.$item_id.'</ItemID>
                        <IncludeSelector>Details,Description,ShippingCosts,ItemSpecifics,Variations</IncludeSelector>
                        </GetSingleItemRequest>';
            $callName = 'GetSingleItem';
            $compatibilityLevel = 647;
            $endpoint = "http://open.api.ebay.com/shopping";
            $headers[] = "X-EBAY-API-CALL-NAME: $callName";
            $headers[] = "X-EBAY-API-APP-ID: APP ID Here";
            $headers[] = "X-EBAY-API-VERSION: $compatibilityLevel";
            $headers[] = "X-EBAY-API-REQUEST-ENCODING: XML";
            $headers[] = "X-EBAY-API-RESPONSE-ENCODING: XML";
            $headers[] = "X-EBAY-API-SITE-ID: 0";
            $headers[] = "Content-Type: text/xml";
            
            $curl = curl_init($endpoint);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
            
            $response = curl_exec($curl);
            return $response = simplexml_load_string($response) ;
            
      }
         
       
}
