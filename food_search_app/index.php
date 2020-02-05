<?php
require_once(__DIR__ . '/config.php');

try {
  $hotpepper = new \MyApp\HotPepper();
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}

$prefectures = $hotpepper->getPrefectures();
$genres = $hotpepper->getGenres();
$restaurants = $hotpepper->getRestaurants();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>レストラン検索</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="container">
      <form action="" method="get" class="pt-4 pb-4">
        <div class="form-group">
          <label for="keyword">キーワード</label>
          <input type="text" class="form-control" name="keyword" id="keyword" value="<?php h(filter_input(INPUT_GET, 'keyword')); ?>">
        </div>
        <div class="form-group">
          <label for="genre">ジャンル</label>
          <select class="form-control" name="genre" id="genre">
            <option value="">ジャンル</option>
            <?php foreach ($genres->results->genre as $genre): ?>
              <?php if ($genre->code == filter_input(INPUT_GET, 'genre')): ?>
                <option value="<?php h($genre->code); ?>" selected="selected">
                  <?php h($genre->name); ?>
                </option>
              <?php else: ?>
                <option value="<?php h($genre->code); ?>">
                  <?php h($genre->name); ?>
                </option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="service_area">都道府県</label>
          <select class="form-control" name="service_area" id="service_area">
            <option value="">都道府県</option>
            <?php foreach ($prefectures->results->service_area as $pref): ?>
              <?php if ($pref->code == filter_input(INPUT_GET, 'service_area')): ?>
                <option value="<?php h($pref->code); ?>" selected="selected">
                  <?php h($pref->name); ?>
                </option>
              <?php else: ?>
                <option value="<?php h($pref->code); ?>">
                  <?php h($pref->name); ?>
                </option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">検索</button>
        </div>
      </form>
      <?php if (isset($restaurants->results)): ?>
        <?php if (isset($restaurants->results->error)): ?>
          <div>
            <?php foreach ($restaurants->results->error as $err): ?>
              <h3><?php h($err->message); ?></h3>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <table class="table table-condensed">
            <thead>
              <tr>
                <th>名前</th>
                <th>画像</th>
                <th>カテゴリー</th>
                <th>最寄駅</th>
                <th>ディナー予算</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($restaurants->results->shop as $rest): ?>
                <tr>
                  <td>
                    <a href="<?php h($rest->urls->pc); ?>">
                      <?php h($rest->name); ?>
                    </a>
                  </td>
                  <td>
                    <img src="<?php h($rest->logo_image); ?>" alt="<?php h($rest->name_kana); ?>">
                  </td>
                  <td><?php h($rest->genre->name); ?></td>
                  <td><?php h($rest->station_name); ?></td>
                  <td><?php h($rest->budget->average); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
              <?php echo $hotpepper->pagination($restaurants->results->results_available); ?>
            </ul>
          </nav>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </body>
</html>
