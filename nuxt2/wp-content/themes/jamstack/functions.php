<?php
  function my_customize_rest_cors() {
    remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
    add_filter( 'rest_pre_serve_request', function( $value ) {
      header( 'Access-Control-Allow-Origin: *' );
      header( 'Access-Control-Allow-Methods: GET' );
      header( 'Access-Control-Allow-Credentials: true' );
      header( 'Access-Control-Expose-Headers: Link', false );
      header( 'Access-Control-Allow-Headers: X-Requested-With' );

      return $value;
    });
  }
  add_action( 'rest_api_init', 'my_customize_rest_cors', 15 );

  add_theme_support( 'post-thumbnails' );
  // 画像追加（これはもういらないかも）
  // add_action('rest_api_init', 'customize_api_response');
  // function customize_api_response() {
  //   register_rest_field(
  //     'post',
  //     'thumbnail',
  //     array(
  //       'get_callback'  => function ($post) {
  //         $thumbnail_id = get_post_thumbnail_id($post['id']);

  //         if ($thumbnail_id) {
  //           // アイキャッチが設定されていたらurl・width・heightを配列で返す
  //           $img = wp_get_attachment_image_src($thumbnail_id, 'large');

  //           return [
  //             'url' => $img[0],
  //             'width' => $img[1],
  //             'height' => $img[2]
  //           ];
  //         } else {
  //           // アイキャッチが設定されていなかったら空の配列を返す
  //           return [];
  //         }
  //       },
  //       'update_callback' => null,
  //       'schema'          => null,
  //     )
  //   );
  // }

  // 投稿タイプ：postを全件取得＋レスポンスを加工
  function add_rest_endpoint_all_posts_from_blog() {
    register_rest_route(
      'wp/api',
      '/post',
      array(
        'methods' => 'GET',
        'callback' => 'get_all_posts_from_blog',
        'permission_callback' => function() { return true; }
      )
    );
  }
  function get_all_posts_from_blog() {
    $args = array(
      'posts_per_page' => -1,
      'post_type' => 'post',
      'post_status' => 'publish'
    );
    $all_posts = get_posts($args);
    $result = array();
    foreach($all_posts as $post) {
      $data = array(
        'id' => $post->ID,
        'thumbnail' => get_the_post_thumbnail_url($post->ID, 'full'),
        'slug' => $post->post_name,
        'date' => $post->post_date,
        'modified' => $post->post_modified,
        'title' => $post->post_title,
        'excerpt' => $post->post_excerpt,
        'content' => $post->post_content
      );
      array_push($result, $data);
    };
    return $result;
  }
  add_action('rest_api_init', 'add_rest_endpoint_all_posts_from_blog');

  // キーワード検索
  function add_rest_endpoint_all_posts_search() {
    register_rest_route(
      'wp/api',
      '/search/(?P<keywords>.*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+)',
      array(
        'methods' => 'GET',
        'callback' => 'get_all_posts_search',
        'permission_callback' => function() { return true; }
      )
    );
  }
  function get_all_posts_search($parameter) {
    $args = array(
      'posts_per_page' => -1,
      'post_type' => array( 'post', 'page', 'blog', 'news' ),
      's' => urldecode($parameter['keywords']),
      'post_status' => 'publish'
    );
    $query = new WP_Query($args);
    $all_posts = $query->posts;
    $result = array();
    foreach($all_posts as $post) {
      $category = '';
      if($post->post_type === 'post') {
        $category = get_the_terms($post->ID, 'category')[0]->name;
      } else if($post->post_type === 'blog') {
        $category = get_the_terms($post->ID, 'blog_category')[0]->name;
      } else if($post->post_type === 'news') {
        $category = get_the_terms($post->ID, 'news_category')[0]->name;
      }
      $data = array(
        'ID' => $post->ID,
        'thumbnail' => get_the_post_thumbnail_url($post->ID, 'full'),
        'slug' => $post->post_name,
        'date' => $post->post_date,
        'modified' => $post->post_modified,
        'title' => $post->post_title,
        'excerpt' => $post->post_excerpt,
        'content' => $post->post_content,
        'category' => $category,
        'post_type' => $post_type
      );
      array_push($result, $data);
    };
    return $result;
  }
  add_action('rest_api_init', 'add_rest_endpoint_all_posts_search');

  // ID指定で個別の記事を取得
  function add_rest_endpoint_single_posts() {
    register_rest_route(
      'wp/api',
      '/blog/(?P<id>[\d]+)',
      array(
        'methods' => 'GET',
        'callback' => 'get_single_posts',
        'permission_callback' => function() { return true; }
      )
    );
  }
  function get_single_posts($parameter) {
    $args_all = array(
      'posts_per_page' => -1,
      'post_type' => 'post',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
    );
    $all_posts = get_posts($args_all);
    $all_posts_ids = array();
    foreach($all_posts as $post) {
      array_push($all_posts_ids, $post->ID);
    }

    $args_single = array(
      'posts_per_page' => 1,
      'post_type' => 'post',
      'post_status' => 'publish',
      'include' => $parameter['id']
    );
    $single_post = get_posts($args_single);
    $single_post_index = !empty($single_post) ? array_search((int) $parameter['id'], $all_posts_ids, true) : -2;
    $prev_post_id = $single_post_index < count($all_posts_ids) - 1 ? $single_post_index + 1 : null;
    $next_post_id = !is_null($single_post_index) && ($single_post_index > 0) ? $single_post_index - 1 : null;
    $targets = array($all_posts[$next_post_id], $single_post[0], $all_posts[$prev_post_id]);
    $result = array();
    foreach($targets as $post) {
      $data = array(
        'ID' => $post->ID,
        'thumbnail' => get_the_post_thumbnail_url($post->ID, 'full'),
        'slug' => $post->post_name,
        'date' => $post->post_date,
        'modified' => $post->post_modified,
        'title' => $post->post_title,
        'excerpt' => $post->post_excerpt,
        'content' => $post->post_content,
        'category' => get_the_terms($post->ID, 'blog_category')[0]->name,
      );
      array_push($result, $data);
    };
    return $result;
  }
  add_action('rest_api_init', 'add_rest_endpoint_single_posts');