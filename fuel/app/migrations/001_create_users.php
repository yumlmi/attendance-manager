<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		// users テーブルを作成
		\DBUtil::create_table('users', array(
			// 主キー
			'id' => array(
				'constraint' => 11,
				'type' => 'int',
				'unsigned' => true,
				'auto_increment' => true,
			),
			// ユーザー名
			'username' => array(
				'constraint' => 50,
				'type' => 'varchar',
			),
			// パスワード（ハッシュ保存を想定）
			'password' => array(
				'constraint' => 255,
				'type' => 'varchar',
			),
			// 学年
			'grade' => array(
				'constraint' => 2,
				'type' => 'int',
				'unsigned' => true,
				'default' => 1,
			),
			// メールアドレス
			'mail' => array(
				'constraint' => 255,
				'type' => 'varchar',
			),
			// 作成日時（UNIXタイムスタンプ）
			'created_at' => array(
				'constraint' => 11,
				'type' => 'int',
				'unsigned' => true,
				'default' => 0,
			),
			// 更新日時（UNIXタイムスタンプ）
			'updated_at' => array(
				'constraint' => 11,
				'type' => 'int',
				'unsigned' => true,
				'default' => 0,
			),
		), array('id'));

		// username の重複を防ぐための一意制約
		\DBUtil::create_index('users', 'username', 'uq_users_username', 'UNIQUE');

		// mail の重複を防ぐための一意制約
		\DBUtil::create_index('users', 'mail', 'uq_users_mail', 'UNIQUE');

		// 学年で絞り込みしやすくするためのインデックス
		\DBUtil::create_index('users', 'grade', 'idx_users_grade');
	}

	public function down()
	{
		// 追加したインデックスを削除
		\DBUtil::drop_index('users', 'uq_users_username');
		\DBUtil::drop_index('users', 'uq_users_mail');
		\DBUtil::drop_index('users', 'idx_users_grade');

		// users テーブルを削除
		\DBUtil::drop_table('users');
	}
}
