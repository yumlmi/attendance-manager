# attendance-manager

## 概要

attendance-manager は、部活動向けの欠席管理を行う Web アプリケーションです。
FuelPHP をベースに、ログインユーザーごとの部活データを扱いながら、遅刻・欠席の登録と確認を行えます。

主な機能:

- 遅刻・欠席の登録
- 日付ごとの遅刻・欠席者一覧の表示
- 部員情報の CRUD（作成・参照・更新・削除）
- 設定画面からのユーザー情報更新

使用技術:

- PHP 7.3
- FuelPHP 1.8
- MySQL 8.0
- Knockout.js（非同期 UI）
- Docker / Docker Compose

## memo.md 条件の解説と該当ファイル

以下は `memo.md` に記載された開発条件の要点と、実装上の該当ファイルです。

### 必須条件

1. サーバサイドは PHP + FuelPHP を使用

- 解説: コントローラ/ビュー/マイグレーションなど FuelPHP の構成で実装。
- 該当ファイル:
  - `composer.json`
  - `fuel/app/classes/controller/base.php`
  - `fuel/app/views/dashboard/index.php`

2. before メソッドを使う

- 解説: 共通前処理（ログイン判定・View 共有値設定など）を `before()` に集約。
- 該当ファイル:
  - `fuel/app/classes/controller/base.php` (`before`)
  - `fuel/app/classes/controller/auth.php` (`before`)
  - `fuel/app/classes/controller/members.php` (`before`)

3. config ファイルをカスタマイズする（独自値）

- 解説: クラブ名や出欠区分など、アプリ独自設定を config で管理。
- 該当ファイル:
  - `fuel/app/config/club_names.php`
  - `fuel/app/config/attendance.php`
  - `fuel/app/classes/controller/auth.php` (`Config::load('club_names', true)`)

4. session / cookie を使う

- 解説: ログイン状態をセッションで保持し、Cookie からの復元も実装。
- 該当ファイル:
  - `fuel/app/classes/controller/base.php` (`Session::get`, `Cookie::get`)
  - `fuel/app/classes/controller/auth.php`

5. ネームスペースを使う（説明できる）

- 解説: Fuel のマイグレーション/タスクで namespace を利用。
- 該当ファイル:
  - `fuel/app/migrations/001_create_users.php` (`namespace Fuel\Migrations;`)
  - `fuel/app/migrations/002_create_attendances.php` (`namespace Fuel\Migrations;`)
  - `fuel/app/tasks/robots.php` (`namespace Fuel\Tasks;`)

6. `\` を使ったグローバル名前空間アクセスの理解

- 解説: クラス参照の衝突回避のため、グローバル参照を利用。
- 該当ファイル:
  - `fuel/app/classes/controller/base.php` (`\Config::load`)
  - `fuel/app/classes/controller/attendanceentry.php` (`\Security::check_token`, `\DB::select`)

7. DB クラスを使ったデータベース操作

- 解説: ORM ではなく `DB::select/insert/update/delete` を使用。
- 該当ファイル:
  - `fuel/app/classes/controller/members.php`
  - `fuel/app/classes/controller/auth.php`
  - `fuel/app/classes/controller/api/attendanceentry.php`

8. 1:n 関係のテーブル構造

- 解説: `users`(1) : `attendances`(n) を `user_id` 外部キーで関連付け。
- 該当ファイル:
  - `fuel/app/migrations/001_create_users.php`
  - `fuel/app/migrations/002_create_attendances.php` (`add_foreign_key`)

9. CRUD 機能を網羅

- 解説: 部員管理で作成・参照・更新・削除を実装。
- 該当ファイル:
  - `fuel/app/classes/controller/members.php` (`action_create`, `action_index`, `action_edit`, `action_delete`)
  - `fuel/app/views/members/index.php`
  - `fuel/app/views/members/form.php`

10. フロントエンドで Knockout.js を使用

- 解説: ViewModel + `data-bind` による MVVM で画面を構築。
- 該当ファイル:
  - `fuel/app/views/dashboard/index.php` (`data-bind`)
  - `public/assets/js/dashboard.js`
  - `public/assets/js/members.js`

11. UX を考慮した動的 UI（非同期処理）

- 解説: `fetch` で API 通信し、画面の部分更新を実施。
- 該当ファイル:
  - `public/assets/js/dashboard.js` (`fetch('/api/dashboard...')`, `fetch('/api/attendanceentry/update')`)
  - `public/assets/js/members.js` (`fetch('/api/users')`)
  - `fuel/app/classes/controller/api/dashboard.php`

12. GitHub でコード管理 / 開発ブランチ + PR 運用

- 解説: `main` とは別に機能ブランチで開発し、PR ベースで統合する運用。
- 該当情報:
  - ローカルブランチ例: `feature/members-crud`, `feature/dashboard-ui-renewal`, `chore/security-coding-rule-fixes`
  - リモートブランチ: `origin/feature/*`, `origin/main`

13. セキュリティ資料を読み、必要実装を行う

- 解説: CSRF/認可/ヘッダ対策など、実装面で主要項目に対応。
- 該当ファイル:
  - `fuel/app/classes/controller/auth.php` (`Security::check_token`)
  - `fuel/app/classes/controller/members.php` (`Security::check_token`)
  - `fuel/app/classes/controller/api/attendanceentry.php` (CSRF + JSON API)
  - `fuel/app/classes/controller/api/dashboard.php` (ログイン確認)
  - `fuel/app/classes/controller/api/users.php` (ログイン確認 + 所属部活で制限)
  - `fuel/app/classes/controller/base.php` (`X-Frame-Options`, `CSP`, `nosniff`)
