<?php
/*
Plugin Name: VinerzZ's Banners
Plugin URI: http://www.legiaodosherois.com.br/
Description: Sistema de banners simples
Author: Vinicius Tavares
Version: 1.0.0
Author URI: http://vinerz.net/
*/

require_once( __DIR__ . '/util.php' );

if( !class_exists('VBanners') ) {
    class VBanners {
        private static $__instance = NULL;
        private function __clone() {}
        
        protected function __construct() {
            add_action( 'init', array(&$this, 'banners_posttype') );
            add_action( 'init', array(&$this, 'cmb_initialize_cmb_meta_boxes'), 9999 );
            add_filter( 'cmb_meta_boxes', array(&$this, 'banners_metaboxes') );
        }
        
        static public function getInstance() {
            if(self::$__instance == NULL) self::$__instance = new VBanners;
            return self::$__instance;
        }
        
        function banners_posttype() {
            register_post_type(
                'banner',
                array(
                    'public' => true,
                    'publicy_queryable' => true,
                    'capability_type' => 'post',
                    'query_var' => true,
                    'hierarchical' => false,
                    'show_ui' => true,
                    'has_archive' => false,
                    'menu_position' => 5,
                    'menu_icon' => 'dashicons-images-alt2',
                    'with_front' => true,
                    'supports' =>	array( 'title', 'author' ),
                    'taxonomies' => array(),
                    'labels' => array(
                        'name' => 'Banners',
                        'singular_name' => 'Banner',
                        'add_new' => 'Adicionar Novo',
                        'add_new_item' => 'Adicionar Novo Banner',
                        'edit' => 'Editar',
                        'edit_item' => 'Editar Banner',
                        'new_item' => 'Novo Banner',
                        'view' => 'Ver Banner',
                        'view_item' => 'Ver Banner',
                        'search_items' => 'Pesquisar Banner',
                        'not_found' => 'Nenhum banner encontrado.',
                        'not_found_in_trash' => 'Nenhum banner encontrado na lixeira.'
                    ),
                )
            );
        }
        
        function banners_metaboxes( array $meta_boxes ) {
            $prefix = '_vin_';
            
            $meta_boxes['banners_editor'] = array(
                'id'         => $prefix . 'banners_editor',
                'title'      => 'Painel de edição de Banner',
                'pages'      => array( 'banner' ),
                'priority'   => 'high',
                'fields'     => array(
                    array(
                        'name' => 'Imagem',
                        'id'   => $prefix . 'cover',
                        'type' => 'file',
                        'description' => 'Escolha a imagem do Banner.'
                    ),
            array(
                        'name' => 'Título',
                        'id'   => $prefix . 'titulo',
                        'type' => 'text'
                    ),
                    array(
                        'name' => 'Descrição',
                        'description' => 'Escreva a descrição do banner',
                        'id'   => $prefix . 'descricao',
                        'type' => 'wysiwyg',
                        'options' => array( 'textarea_rows' => 3 )
                    ),
                    array(
                        'name' => 'Link',
                        'id'   => $prefix . 'link',
                        'type' => 'text'
                    )
                )
            );

            return $meta_boxes;
        }
        
        function cmb_initialize_cmb_meta_boxes() {
            if ( ! class_exists( 'cmb_Meta_Box' ) )
                require_once( __DIR__ . '/framework/init.php' );
        }
        
        function listBanners( $qty = 3 ) {
            $args = array(
                'post_type' => 'banner',
                'posts_per_page' => (int) $qty,
            );
            
            $query = new WP_Query( $args );
            
            return $query->posts;
        }
    }
}

$vbanners = VBanners::getInstance();

if( !function_exists('vb_get_banners') ) {
    function vb_get_banners() {
        $vbanners = VBanners::getInstance();
        return $vbanners->listBanners();
    }
}