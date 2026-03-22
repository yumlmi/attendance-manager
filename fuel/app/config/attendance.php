<?php

/**
 * 欠席・遅刻管理システム固有の設定
 */

return array(
	/**
	 * 遅刻・欠席の種別定義
	 */
	'types' => array(
		'absence' => '欠席',
		'late' => '遅刻',
	),

	/**
	 * 理由のプリセット
	 */
	'reasons' => array(
		'sick' => '体調不良',
		'emergency' => '緊急事態',
		'personal' => '私事',
		'family' => '家族事由',
		'unknown' => '不明',
	),

	/**
	 * 学年ラベル
	 */
	'grades' => array(
		1 => '1年生',
		2 => '2年生',
		3 => '3年生',
	),

	/**
	 * ページネーション設定
	 */
	'pagination' => array(
		'per_page' => 20,  // 1ページあたりの件数
	),

	/**
	 * ダッシュボード表示設定
	 */
	'dashboard' => array(
		'recent_days' => 7,  // 最近N日間の遅刻・欠席を表示
	),

	/**
	 * 認証・セキュリティ設定
	 */
	'auth' => array(
		// true の場合、Authルートを HTTPS に強制する（推奨デフォルト）
		// 開発都合で false にする場合でも、HTTP では remember-me Cookie は発行しない実装
		'force_https' => true,

		// HTTPS強制時のリダイレクト先ベースURL（例: https://example.com）
		// 未設定時は Uri::create(..., true) で現在URIをHTTPS化して使用
		'https_redirect_base_url' => '',

		// remember-me Cookie に secure 属性を付与するか
		// true の場合、HTTPS 以外では remember-me を発行しない
		'remember_cookie_secure' => true,
	),
);
