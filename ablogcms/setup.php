<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>a-blog cms インストーラー ((´ ºムº `)版)</title>
</head>
<body>
<?php

set_time_limit(0);

// --------------------------
//
// macOS MAMP用 a-blog cms 2.9.x 簡単セットアップ
//
// --------------------------

$ablogcmsVersion = ""; #サイトからバージョンを自動チェック

# ERROR になる場合や 2.9系のバージョンを
# 指定したい場合には、バージョンを設定してください。

#$ablogcmsVersion = "2.9.0";

// --------------------------
// 現在の a-blog cms のバージョンをチェック
// --------------------------

if (!$ablogcmsVersion) {
  $check = download_version_check ();
  if ($check) {
    $ablogcmsVersion = $check;
  } else {
    echo "web site version check error.";
    exit;
  }
}

// --------------------------

# ダウンロード元 URL
$download55 = sprintf("http://developer.a-blogcms.jp/_package/%s/acms%s_php5.3.zip",$ablogcmsVersion,$ablogcmsVersion);
$download56 = sprintf("http://developer.a-blogcms.jp/_package/%s/acms%s_php5.6.zip",$ablogcmsVersion,$ablogcmsVersion);
$download71 = sprintf("http://developer.a-blogcms.jp/_package/%s/acms%s_php7.1.zip",$ablogcmsVersion,$ablogcmsVersion);


# ダウンロード後のZipファイル名
$zipFile = "./acms_install.zip";

# 解凍後の全体フォルダ名
$zipAfterDirName55 = sprintf("acms%s_php5.3",$ablogcmsVersion);
$zipAfterDirName56 = sprintf("acms%s_php5.6",$ablogcmsVersion);
$zipAfterDirName71 = sprintf("acms%s_php7.1",$ablogcmsVersion);


# 現在の PHP のバージョンを設定
$versionArray = explode(".", phpversion());
$version = $versionArray[0].".".$versionArray[1];


// --------------------------
// 動作チェック
// --------------------------

if (is_file("./license.php")) {
  echo "Installation error. Please use the updated version.";
  exit;
}

// --------------------------
// バージョンのチェック
// --------------------------

if ($versionArray[0]==7 && $versionArray[1] > 0) {
   $download = $download71;
   $zipAfterDirName = $zipAfterDirName71;
} elseif ($versionArray[0] == 7 && $versionArray[1] == 0) {
   $download = $download56;
   $zipAfterDirName = $zipAfterDirName56;
} elseif ($versionArray[1] >= 6) {
    $download = $download56;
    $zipAfterDirName = $zipAfterDirName56;
} else {
    $download = $download55;
    $zipAfterDirName = $zipAfterDirName55;
}

# 解凍後の a-blog cms のフォルダ名
$cmsDirName = "ablogcms";

// # ioncube Loader ダウンロード元 URL
// $downloadIoncube = "http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_dar_x86-64.zip";

// # ioncube Loader ダウンロード後のZipファイル名
// $zipFileIoncube ="ioncube.zip";

$installPath = realpath('.');

// $phpName = basename($_SERVER['PHP_SELF']);

$ablogcmsDir = $installPath."/".$zipAfterDirName."/".$cmsDirName."/";

// $ablogcmsVersionNum = str_replace(".", "", $ablogcmsVersion);

// $mampRestart = "";

// --------------------------
// データベースの設定
// --------------------------

// $dbHost     = 'localhost';
// $dbName     = 'DBacms_'.$ablogcmsVersionNum."_".date(mdHi);
// $dbCreate   = 'checked';
// $dbUser     = 'root';
// $dbPass     = 'root';

// --------------------------
// a-blog cms ファイルをダウンロード
// --------------------------

$fp = fopen($download, "r");
if ($fp !== FALSE) {
    file_put_contents($zipFile, "");
    while(!feof($fp)) {
        $buffer = fread($fp, 4096);
        if ($buffer !== FALSE) {
            file_put_contents($zipFile, $buffer, FILE_APPEND);
        }
    }
    fclose($fp);
} else {
    echo 'a-blog cms download Error ! : '.$download;
    exit;
}

// --------------------------
// a-blog cms ファイルを解凍
// --------------------------

$zip = new ZipArchive();
$res = $zip->open($zipFile);

if($res === true){
    $zip->extractTo($installPath);
    $zip->close();

} else {
    echo 'a-blog cms unZip Error ! : '. $zipFile;
    exit;
}

// --------------------------
// a-blog cms ディレクトリを移動
// --------------------------

if ($handle = opendir($ablogcmsDir)) {
    while(false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
             rename($ablogcmsDir.$entry, $installPath ."/". $entry);
        }
    }
    closedir($handle);
} else {
    echo 'a-blog cms move Error ! :'.$ablogcmsDir;
    exit;
}

// --------------------------
// ioncube Loader チェック
// --------------------------

// $useIonCubeLoader = sprintf("ioncube_loader_dar_%d.%d.so",$versionArray[0],$versionArray[1]);

// if (!is_file(PHP_EXTENSION_DIR."/".$useIonCubeLoader)) {


    // --------------------------
    // ioncube ファイルをダウンロード
    // --------------------------

    // $fp = fopen($downloadIoncube, "r");
    // if ($fp !== FALSE) {
    //     file_put_contents($zipFileIoncube, "");
    //     while(!feof($fp)) {
    //         $buffer = fread($fp, 4096);
    //         if ($buffer !== FALSE) {
    //             file_put_contents($zipFileIoncube, $buffer, FILE_APPEND);
    //         }
    //     }
    //     fclose($fp);
    // } else {
    //     echo 'ioncube loader download Error ! : '.$download;
    //     exit;
    // }

    // --------------------------
    // ioncube Loader ファイルを解凍
    // --------------------------

    // $zip = new ZipArchive();
    // $res = $zip->open($zipFileIoncube);

    // if($res === true){
    //     $zip->extractTo($installPath);
    //     $zip->close();

    // } else {
    //     echo 'ioncube loader unZip Error ! : '. $zipFileIoncube;
    //     exit;
    // }

    // --------------------------
    // ioncube Loader ファイルを移動
    // --------------------------

    // rename("./ioncube/".$useIonCubeLoader, PHP_EXTENSION_DIR."/".$useIonCubeLoader);

    // --------------------------
    // php.ini の設定
    // --------------------------

    // # MAMP の php.ini のパスを設定する
    // $iniFile = "/Applications/MAMP/bin/php/php".phpversion()."/conf/php.ini";

    // # 追記する設定内容
    // $iniData = sprintf("\n\ndate.timezone = 'Asia/Tokyo'\n\nzend_extension = \"%s/ioncube_loader_dar_%d.%d.so\"",PHP_EXTENSION_DIR ,$versionArray[0],$versionArray[1]);

    // $file = file_get_contents($iniFile);

    // if (preg_match("/ioncube_loader/i", $file)) {

    //   # 設定済み

    // } else {
    //   $file = fopen( $iniFile, "a+" );
    //   fwrite( $file, $iniData );
    //   fclose( $file );
    // }

    // $mampRestart = "<strong>MAMPを再起動して</strong> ";

// }


// --------------------------
// .htaccess の設定
// --------------------------

rename("./htaccess.txt", './.htaccess');
rename("./archives/htaccess.txt", './archives/.htaccess');
rename("./archives_rev/htaccess.txt", './archives_rev/.htaccess');
rename("./private/htaccess.txt", './private/.htaccess');
rename("./media/htaccess.txt", './media/.htaccess');
rename("./theme/htaccess.txt", './theme/.htaccess');

// --------------------------
// DB 初期設定
// --------------------------

// $data = sprintf("<?php
// \$dbDefaultHost     = '%s';
// \$dbDefaultName     = '%s';
// \$dbDefaultCreate   = '%s'; // '' or 'checked'
// \$dbDefaultUser     = '%s';
// \$dbDefaultPass     = '%s';
// \$dbDefaultPrefix   = 'acms_';",$dbHost,$dbName,$dbCreate,$dbUser,$dbPass);
// $db_default = "./setup/lib/db_default.php";
// file_put_contents($db_default, $data);

// --------------------------
// ファイルの削除
// --------------------------

unlink($zipFile);
// unlink($zipFileIoncube);
// unlink($phpName);

# index.html があった時にリネームしておく
if (is_file("./index.html")) {
    rename("./index.html", "_index.html");
}

# unlink($installPath."/ioncube/loader-wizard.php");
// dir_shori ("delete", "ioncube");

# プログラム以外のディレクトリを削除
dir_shori ("delete", $zipAfterDirName);

// --------------------------
// インストーラーに飛ぶ
// --------------------------

echo sprintf('<p style="text-align:center; margin-top:100px">a-blog cms Ver %s ( php %s ) をインストールしました。</p>',$ablogcmsVersion,$version);

$jump = "http://".$_SERVER['HTTP_HOST']."/";
echo sprintf('<p style="text-align:center; margin-top:30px">%s<a href="%s">%s</a> にアクセスしてください。</p>',$mampRestart, $jump, $jump);

// --------------------------
// ディレクトリを操作 function ( move / copy / delete )
// --------------------------

function dir_shori ($shori, $nowDir , $newDir="") {

  if ($shori != "delete") {
    if (!is_dir($newDir)) {
      mkdir($newDir);
    }
  }

  if (is_dir($nowDir)) {
    if ($handle = opendir($nowDir)) {
      while (($file = readdir($handle)) !== false) {
        if ($file != "." && $file != "..") {
          if ($shori == "copy") {
            if (is_dir($nowDir."/".$file)) {
              dir_shori("copy", $nowDir."/".$file, $newDir."/".$file);
            } else {
              copy($nowDir."/".$file, $newDir."/".$file);
            }
          } elseif ($shori == "move") {
            rename($nowDir."/".$file, $newDir."/".$file);
          } elseif ($shori == "delete") {
            if (filetype($nowDir."/".$file) == "dir") {
              dir_shori("delete", $nowDir."/".$file, "");
            } else {
              unlink($nowDir."/".$file);
            }
          }
        }
      }
      closedir($handle);
    }
  }

  if ($shori == "move" || $shori == "delete") {
    rmdir($nowDir);
  }

  return true;
}

function download_version_check () {

  // Version 2.9.x のチェック用
  // 正常にチェックできない場合には 空 でかえす。

  $options['ssl']['verify_peer']=false;
  $options['ssl']['verify_peer_name']=false;
  $html=file_get_contents('https://developer.a-blogcms.jp/download/', false, stream_context_create($options));
  preg_match('/<h1 class="entry-title" id="(.*)"><a href="https:\/\/developer.a-blogcms.jp\/download\/package\/2.9.(.*).html">(.*)<\/a><\/h1>/',$html,$matches);

  if (is_numeric($matches[2])) {
    return "2.9.".$matches[2];
  } else {
    return;
  }

}


?>
</body>
</html>