# README #

http://35.234.27.103/backend/

## 工具使用

### 遠端登入

```
ssh dev-remote@35.234.27.103 -i ../dev-remote
```

### API 文件產生

** Install Apidoc **
```
npm install apidoc -g
```

輸出產生可以透過以下指令，如還尚未安裝APIDOC，透過上方去安裝。
```
$ apidoc -i application/ -o apidoc/
```

## 資料架構
### 用戶篇
```
user_main => 使用者資料
```