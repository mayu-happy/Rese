## アプリケーション名
Rese（飲食店予約サービスアプリ）

---

## 作成した目的
グループ会社向けの飲食店予約サービスを想定し、外部サービス利用時の手数料負担を抑えながら、自社で予約管理ができる仕組みを構築することを目的として作成しました。  
店舗情報の閲覧、予約、予約管理、レビュー投稿までを一貫して行えるサービスとして設計・開発しています。

---

## アプリケーションURL
現在はローカル環境でのみ動作確認を行っています。

- アプリケーション：`http://localhost`
- ログインURL：`http://localhost/login`
- phpMyAdmin：`http://localhost:8080`

### ログインに関する注意事項
- 会員登録後、ログインして利用できます
- 予約機能、お気に入り機能、レビュー投稿機能はログインが必要です
- 現在より前の日時は予約できません
- 過去の予約は変更できません
- レビューは来店済みの店舗に対してのみ投稿できます

---

## 他のリポジトリ
関連するリポジトリはありません。

---

## 機能一覧
- 会員登録機能
- ログイン機能
- ログアウト機能
- 飲食店一覧表示機能
- 飲食店詳細表示機能
- 飲食店検索機能
- 飲食店予約機能
- 予約変更機能
- 予約キャンセル機能
- マイページ表示機能
- お気に入り追加・解除機能
- レビュー投稿機能
- メニュー画面表示機能

---

## 使用技術（実行環境）
- PHP 8.1.34
- Laravel 8.83.29
- MySQL 8.0.26
- nginx 1.21.1
- Docker 29.2.1
- Docker Compose plugin v2.5.1
- phpMyAdmin

---

## テーブル設計
主なテーブルは以下の通りです。

- users
- shops
- reservations
- favorites
- areas
- genres
- reviews

![テーブル設計](./images/table-design.png)

---

## ER図

![ER図](./images/er-diagram.png)

---

## 環境構築

### 1) リポジトリを取得してディレクトリに入る

```bash
git clone git@github.com:mayu-happy/Rese.git
cd Rese
```

---

### 2) Docker ビルド & 起動

```bash
docker compose up -d --build
```

---

### 3) Laravel 環境構築（コンテナ内）

```bash
docker compose exec php bash
```

> ⚙️ **補足：src ディレクトリ構成について**  
> このプロジェクトは **`src/`** に Laravel 本体が入っています。  
> 以降の `composer` / `php artisan` コマンドは、**コンテナ内 `/var/www`（= ホストの src/）** で実行してください。  
> `pwd` で `/var/www`、`ls artisan` で `artisan` が見えることを毎回確認すると安全です。

---

### 4) Laravelの準備（依存インストールと `.env` 作成）

```bash
composer install
cp .env.example .env
```

`.env` の DB 設定をこのように変更してください（抜粋）：

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

`.env` の MAIL 設定をこのように変更してください（抜粋）：

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5)  アプリケーションキーの作成
``` bash
php artisan key:generate
```

### 6) マイグレーション＆初期データ投入
``` bash
php artisan migrate --seed
```

### 7) ストレージを公開
``` bash
php artisan storage:link
```

## 🧩 トラブルシュート：storage/logs の Permission denied エラー

### 💡 発生するエラー例

テストや画面表示時に、次のようなエラーが出ることがあります。

```text
UnexpectedValueException
The stream or file "/var/www/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
```

---

### 🔍 原因

- `storage` や `bootstrap/cache` ディレクトリの所有者・権限が原因で  
  Laravel がログファイル（`storage/logs/laravel.log`）に書き込めなくなっている状態です。

---

### 🛠 対処手順（PHPコンテナ内で実行）

1. PHPコンテナに入る

```bash
docker compose exec php bash
cd /var/www
```

2. `storage` / `bootstrap/cache` の権限を修正

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

3. （必要に応じて）ログファイルを作り直す

```bash
rm -f storage/logs/laravel.log
```

> 💡 `laravel.log` は Laravel が自動で作り直すので、削除しても問題ありません。

その後、もう一度アプリを表示したり、テストを実行してください。

```bash
php artisan test
```

✅ 上記の権限修正後は、同じエラーは解消されるはずです。


---

## PHPUnit テスト実行手順

### 1) コンテナ起動（ホスト側、プロジェクトルート `Rese` ディレクトリで）

```bash
docker compose up -d
docker compose ps
```

---

### 2) 依存導入＆アプリキー作成（初回のみ／PHPコンテナ内で実行）

```bash
docker compose exec php bash
composer install
cp -n .env.example .env || true
```

> すでに環境構築済みの場合は、このステップはスキップしてOKです。

---

### 3) テストDB用 `.env.testing` の作成

```bash
cp .env .env.testing
php artisan key:generate --env=testing
```

`.env.testing` 内の DB 設定をテスト用に変更してください（例）：

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

---

### 4) テスト用マイグレーション（testing 環境）

```bash
php artisan migrate:fresh --env=testing --no-interaction
```

---

### 5) テスト実行

```bash
php artisan test
```

または詳細表示付き：

```bash
vendor/bin/phpunit --testdox
```

---

## 🧩 トラブルシュート：テスト用DBへのアクセス権エラー

### 💡 発生するエラー例

```bash
php artisan migrate:fresh --env=testing --no-interaction
```

実行時に以下のようなエラーが出ることがあります。

```bash
SQLSTATE[HY000] [1044] Access denied for user 'laravel_user'@'%' to database 'laravel_test_db'
```

---

### 🔍 原因

- MySQL にテスト用データベース `laravel_test_db` がまだ存在しない  
- またはユーザー `laravel_user` に `laravel_test_db` への権限が付与されていない

---

### 🛠 対処手順（Docker + MySQL 環境）

#### 1. MySQLコンテナに入る

```bash
docker compose exec mysql bash
```

#### 2. rootユーザーでMySQLにログイン

```bash
mysql -u root -p
```

> 💡 パスワードは `docker-compose.yml` で指定した  
> `MYSQL_ROOT_PASSWORD`（例：`root`）を入力してください。

#### 3. テスト用DBを作成し、権限を付与

```sql
CREATE DATABASE IF NOT EXISTS laravel_test_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON laravel_test_db.* TO 'laravel_user'@'%';
FLUSH PRIVILEGES;
EXIT;
```

#### 4. PHPコンテナでマイグレーション再実行

```bash
docker compose exec php bash
php artisan migrate:fresh --env=testing --no-interaction
```

---

### ⚙️ `.env.testing` の設定確認

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_test_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

✅ これで `php artisan migrate:fresh --env=testing` が正常に動作し、  
PHPUnit テストを実行できる状態になります！

---

