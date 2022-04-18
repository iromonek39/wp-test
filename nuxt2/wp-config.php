<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link https://ja.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define( 'DB_NAME', 'wp' );

/** MySQL データベースのユーザー名 */
define( 'DB_USER', 'root' );

/** MySQL データベースのパスワード */
define( 'DB_PASSWORD', 'root' );

/** MySQL のホスト名 */
define( 'DB_HOST', 'localhost' );

/** データベースのテーブルを作成する際のデータベースの文字セット */
define( 'DB_CHARSET', 'utf8mb4' );

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define( 'DB_COLLATE', '' );

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Yn5pqo8Hof!Nn>*$Mv{2B,1UM6W#4xcec)<`-(Y]8MEMzw-`tdA{m+Bk^xa+@em(' );
define( 'SECURE_AUTH_KEY',  'EIAUMcx_mS7 ~hB9PD/@V4;9Uci7tNf74c; #i}9255{#u(::&%/eqB6 7>:Lbd+' );
define( 'LOGGED_IN_KEY',    'k<#PM34FbLS)br[9<frHX<&=`o%nxH`$,ZcwRARMX=g{}rn%5^E>B{VLk7rySo3C' );
define( 'NONCE_KEY',        'a*3m[-F FTb]}iUQn=LoXSEa5`{;U1VVbq}JI>.)-$xg +S-}@m*<Udo^O/}_fx:' );
define( 'AUTH_SALT',        'ez>qKlh4 |RdvfNIga1QD*sWklD~-!/jkf~0A.KSu&dR/<Oaw58p(og5.VU-8~7<' );
define( 'SECURE_AUTH_SALT', 'hR3W3HDwBLsXcA:nTV(0pfin4fz!ZkuU}o>ESX}$}%g`d*e`fUVrNF^V@Q%,oi#*' );
define( 'LOGGED_IN_SALT',   '%-jV)!2}uZ=5GA%V:X,GIcVq02siSpko3..GV!bQWOzX[XN(?C3|,VB|%#K2K&j&' );
define( 'NONCE_SALT',       '{oKU%>zf$YcNC9,~Ms(]tmC7pq}v3.nhu,5o7*w_`ymb.=vxIge=Ahzh_CV)SiQ6' );

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数についてはドキュメンテーションをご覧ください。
 *
 * @link https://ja.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* カスタム値は、この行と「編集が必要なのはここまでです」の行の間に追加してください。 */



/* 編集が必要なのはここまでです ! WordPress でのパブリッシングをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
