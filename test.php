<?php

// 関数作成追加
function getExtension($mime_type)
{
    $extensions = [
                    'image/jpeg' => 'jpeg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/bmp' => 'bmp',
                    ];
    return $extensions[$mime_type];
}

// もしフォームの送信がおかしければ以下エラー文出して処理を終了させる。
if ($_POST['base64_image'] === '') {
    exit('ブラウザバックして、もう一度やり直してください。');
}

// postされたデータの文頭「data:image/png;base64」を消す
// 参考 https://stackoverflow.com/questions/15153776/convert-base64-string-to-an-image-file
$data = explode(',', $_POST['base64_image']);

// base64をデコード
$image = base64_decode($data[1]);

// fileのmimeタイプ取得(image/jpegが送られてくるので、多分この処理は不要)
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->buffer($image);

// fileの拡張子取得
$extension = getExtension($mime_type);

// 画像保存先フォルダ（imgフォルダ）がなければ作成
if (!file_exists('img')) {
    mkdir('img', 0777);
}
// imgファイルパーミッション変更がうまくいかないので明示的指定
chmod('img', 0777);

// ファイルの保存(名前は、日付 + _image.jpeg の形にしてます。)
$file_name = date('YmdHis')."_image.{$extension}";
file_put_contents('img/' . $file_name, $image);

if (file_exists('img/' . $file_name)) {
    // 作業しやすい様に、権限変えちゃってます。
    chmod('img/' . $file_name, 0777);
    echo 'imgフォルダに保存しました。';
    echo '保存名 : ' . 'img/' . $file_name;
} else {
    echo '保存できませんでした。.';
}
