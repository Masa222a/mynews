1.【超応用】 プロフィールの更新履歴を保存する仕組みを作るには
どのようにしたらよいでしょうか。手順をまとめてみましょう。

Migrationファイルの作成(create_profile_histories_table)
・使いたい機能のために必要な情報などを記述
                    ↓
migrationを実行
                    ↓
Modelファイルの作成(ProfileHistory)
・主キーのセット
・必須項目の記述
                    ↓
Profile Modelに追記
・リレーション機能(hasMany)を使い
ProfileHistoryを関連付ける
                    ↓
ProfileControllerのupdate ActionにProfileを保存すると同時に
更新履歴を追加するように実装
・Carbonライブラリを使用し時刻を取得
・上記で取得した時刻をProfileHistoryモデルのchange_log_atに記録
                    ↓
ProfileControllerに使用するモデル、ライブラリの記載
　　　　　　　　　　↓
更新履歴を編集画面で参照できるように編集
・resources/view/admin/profile/edit.blade.phpを編集
・@foreachで一つずつ履歴を表示
