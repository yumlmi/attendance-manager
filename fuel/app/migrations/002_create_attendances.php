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
	}

	public function down()
	{
		// attendances テーブルを削除
		\DBUtil::drop_table('attendances');
	}
}
