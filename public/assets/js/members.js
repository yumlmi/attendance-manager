// public/assets/js/members.js
// Knockout.js ViewModel for 部員一覧

/**
 * 部員1人分のデータモデル
 * @param {Object} data - APIから取得したユーザーデータ
 */
function Member(data) {
    this.id = data.id;
    this.username = ko.observable(data.username); // 氏名
    this.grade = ko.observable(data.grade);       // 学年
    this.mail = ko.observable(data.mail);         // メールアドレス
}

/**
 * 部員一覧画面用ViewModel
 * usersテーブルの全ユーザーをAPI経由で取得し、テーブルにバインド
 */
function MembersViewModel() {
    var self = this;
    // 部員リスト（observableArrayでknockout.jsとバインド）
    self.members = ko.observableArray([]);

    /**
     * APIから部員一覧を取得し、membersにセット
     * /api/users でusersテーブルの全ユーザー情報を取得
     */
    self.fetchMembers = function() {
        fetch('/api/users')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                // APIレスポンスのusers配列をMemberオブジェクトに変換
                var mapped = data.users.map(function(m) { return new Member(m); });
                self.members(mapped);
            })
            .catch(function(error) {
                console.error('API取得エラー:', error);
            });
    };

    // 画面初期化時にAPIから部員一覧を取得
    self.fetchMembers();
}

// DOMロード時にViewModelをバインド
document.addEventListener('DOMContentLoaded', function() {
    ko.applyBindings(new MembersViewModel());
});
