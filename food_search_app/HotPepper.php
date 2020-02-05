<?php
namespace MyApp;

class HotPepper {
  // 取得済みAPIキー
  private static $token = '2db2fc193e5eb8a0';

  // 都道府県名を取得時に使用
  public function getPrefectures() {
    $uri = "https://webservice.recruit.co.jp/hotpepper/service_area/v1/";
    $acckey = self::$token;
    $format = "json";

    $url = sprintf("%s?format=%s&key=%s", $uri, $format, $acckey);
    $json = file_get_contents($url);
    $obj = json_decode($json);
    return $obj;
  }

  // ジャンル名を取得時に使用
  public function getGenres() {
    $uri = "https://webservice.recruit.co.jp/hotpepper/genre/v1/";
    $acckey = self::$token;
    $format = "json";

    $url = sprintf("%s?format=%s&key=%s", $uri, $format, $acckey);
    $json = file_get_contents($url);
    $obj = json_decode($json);
    return $obj;
  }

  // レストラン情報を取得時に使用
  public function getRestaurants() {
    $uri = "http://webservice.recruit.co.jp/hotpepper/gourmet/v1/";
    $acckey = self::$token;
    $format = "json";

    // GETパラメータを格納する配列
    $get = [
        'format' => $format,
         'key' => $acckey,
         'count' => 10,
         'name_any' => ''
    ];

    // フォームからの入力があるか判定
    if (!is_null(filter_input_array(INPUT_GET))) {
      // 配列の結合
      $get += filter_input_array(INPUT_GET);
    }

    $url = sprintf("%s?%s", $uri, http_build_query($get));
    $json = file_get_contents($url);
    $obj = json_decode($json);
    return $obj;
  }

  public function pagination($total = 0) {
    $start = filter_input(INPUT_GET, 'start');  // 現在のページ最初の要素の番号

    // 検索結果がない時
    if ($start == 0) {
      $start = 1;
    }
    if ($total == 0) {
      return;
    }

    $liNum = 7;  // 表示するページアイテムの数
    $liCenter = ceil($liNum / 2);  // 表示するページアイテムの中央の数字
    $cnt = 10; // 表示する検索結果の数

    $currentPage = ceil($start / $cnt);  // 現在のページ番号
    $iStart = ($currentPage >= $liCenter) ? $currentPage - $liCenter + 1 : 1;  // 表示するページ番号の最初

    $pages = ceil($total / $cnt);  // ページ総数
    $iMax = ($iStart + $liNum - 1 > $pages) ? $pages : $iStart + $liNum - 1;  // 表示するページ番号の最後

    $array = [];  // ページアイテムを格納する配列
    $params = filter_input_array(INPUT_GET);  // GETパラメータを格納する配列

    // 前ページへ
    $html = '<li class="page-item%s">';
    $html .= '<a class="page-link" href="?%s" aria-label="Previous">';
    $html .= '<span aria-hidden="true">&laquo;</span></a></li>';

    $params['start'] = $start - $cnt;  // 付与するGETパラメータ
    if ($params['start'] < 1) {
      $params['start'] = 1;
    }
    $class = ($start == 1) ? ' disabled' : '';  // ページアイテムに付与するクラス
    $query = http_build_query($params, '', '&amp;');  // GETパラメータを'&'で連結
    $array[] = sprintf($html, $class, $query);  // ページアイテムを格納

    // ページ番号
    $html = '<li class="page-item%s"><a class="page-link" href="?%s">%s</a></li>';

    for ($i = $iStart; $i <= $iMax; $i++) {
      $params['start'] = ($i - 1) * $cnt + 1;
      $class = ($params['start'] == $start) ? ' active' : '';
      $query = http_build_query($params, '', '&amp;');
      $array[] = sprintf($html, $class, $query, $i);
    }

    // 次ページへ
    $html = '<li class="page-item%s">';
    $html .= '<a class="page-link" href="?%s" aria-label="Next">';
    $html .= '<span aria-hidden="true">&raquo;</span></a></li>';

    $params['start'] = $start + $cnt;
    if ($start > $total) {
      $params['start'] = ($pages - 1) * $cnt + 1;
    }
    $class = ($start > $total - $cnt) ? ' disabled' : '';
    $query = http_build_query($params, '', '&amp;');
    $array[] = sprintf($html, $class, $query);

    return implode(PHP_EOL, $array);
  }
}
