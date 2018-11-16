# a-blog cms ローカル開発環境

dockerでa-blog cmsの開発環境を整えるためのものに、調整を加えたもの。

[Docker Community Edition for Mac](https://store.docker.com/editions/community/docker-ce-desktop-mac) で動作します。 Windows でも動作するとは思いますが、未検証です。

元：[https://github.com/appleple/ablogcms-docker](https://github.com/appleple/ablogcms-docker)

## Container

* [mysql](https://hub.docker.com/_/mysql/)
* [jwilder/nginx-proxy](https://hub.docker.com/r/jwilder/nginx-proxy/)
* [atsu666/ioncube](https://hub.docker.com/r/atsu666/ioncube/)

## Commands

### 起動

```bash
docker-compose up -d
```

### 停止

```bash
docker-compose stop
```

### 削除

```bash
docker-compose down
```


## 設定など

`hosts` ファイルに以下を書き加えます

```
127.0.0.1 acms.lab
```

## 補足

### データベース

設定したデータベースが `ablogcms/db/mysql_data/*` に残るようにしています