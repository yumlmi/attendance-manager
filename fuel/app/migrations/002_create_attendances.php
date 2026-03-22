<?php

namespace Fuel\Migrations;

class Create_attendances
{
	public function up()
	{
		// attendances テーブルを作成
		\DBUtil::create_table('attendances', array(
			// 主キー
			'id' => array(
				'constraint' => 11,
				'type' => 'int',
				'unsigned' => true,
				'auto_increment' => true,
			),
			// users.id を参照するユーザーID（FKは次タスクで追加）
			'user_id' => array(
				'constraint' => 11,
				'type' => 'int',
				'unsigned' => true,
			),
			// 遅刻・欠席の日付
			'attendance_date' => array(
				'type' => 'date',
			),
			// 種別（absence / late）
			'type' => array(
				'constraint' => 20,
				'type' => 'varchar',
			),
			// 理由（任意入力）
			'reason' => array(
				'constraint' => 255,
				'type' => 'varchar',
				'null' => true,
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

		// 外部キー制約: user_id -> users.id（削除時はRESTRICT）
		\DBUtil::add_foreign_key('attendances', 'user_id', 'users', 'id', 'RESTRICT', 'RESTRICT');

		// 複合インデックス: (user_id, attendance_date) でユーザーごとの日付検索を高速化
		\DBUtil::create_index('attendances', array('user_id', 'attendance_date'), 'idx_attendances_user_date');
	}

	public function down()
	{
		// 複合インデックスを削除
		\DBUtil::drop_index('attendances', 'idx_attendances_user_date');

		// 外部キー制約を削除
		\DBUtil::drop_foreign_key('attendances', array('user_id'));

		// attendances テーブルを削除
		\DBUtil::drop_table('attendances');
	}
}
