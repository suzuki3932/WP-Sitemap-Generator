<?php
/*
Plugin Name: WP Sitemap Generator
Description: 最もシンプルなサイトマップ生成プラグイン。
Version: 1.0
Author: Myon
Author URI: https://youmutech.cloudfree.jp/
Requires PHP: 7.4
Requires at least: 5.5
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

add_filter( 'wp_sitemaps_enabled', '__return_false' );

// プラグインの初期化
function my_custom_sitemap_init() {
    // サイトマップを生成するフックを登録
    add_action( 'init', 'generate_sitemap' );
}
add_action( 'plugins_loaded', 'my_custom_sitemap_init' );

// サイトマップを生成する関数
function generate_sitemap() {
    // 出力バッファを開始
    ob_start();

    // XMLヘッダーを出力
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // 投稿を取得し、XML要素を生成
    $args = array(
        'post_type' => array('post', 'page'), // 投稿タイプを指定
        'posts_per_page' => -1, // 全ての投稿を取得
    );
    $query = new WP_Query( $args );

    while ( $query->have_posts() ) {
        $query->the_post();
        echo '<url>';
        echo '<loc>' . get_permalink() . '</loc>';
        echo '<lastmod>' . get_the_modified_date('Y-m-d') . '</lastmod>';
        echo '</url>';
    }

    // XMLフッターを出力
    echo '</urlset>';

    // 出力バッファの内容をファイルに保存
    $file_path = ABSPATH . 'sitemap.xml';
    file_put_contents( $file_path, ob_get_clean() );

    // WP_Queryをリセット
    wp_reset_postdata();
}
