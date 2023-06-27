## 環境概要
PHP8.1
MySQL8.0
nginx
Laravel9

## ローカル環境構築手順
1. ルートディレクトリで`docker-compose up -d --build`を実行。
2. **すでにプロジェクトが生成されている場合は不要** Laravelのプロジェクトがない場合は`docker-compose exec app composer create-project --prefer-dist laravel/laravel`を実行。バージョンの指定をする場合は末尾に`:^9.0`を追記する。本プロジェクトはバージョン9を採用。
3. コンテナに入る`docker-compose exec -u www-data app bash`のあとに`cd laravel`
   1. `ln -s public storage/app/public`を実行。
   2. `composer install`を実行。
   3. 新たにインストールされた依存関係のためにLaravelのオートローダを再生成`composer dump-autoload`
   4. `php artisan migrate`を実行
   5. `php artisan storage:link`を実行