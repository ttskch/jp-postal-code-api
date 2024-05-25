# 日本の郵便番号API

[![](https://github.com/ttskch/jp-postal-code-api/actions/workflows/ci.yaml/badge.svg?branch=main)](https://github.com/ttskch/jp-postal-code-api/actions/workflows/ci.yaml?query=branch:main)
[![codecov](https://codecov.io/gh/ttskch/jp-postal-code-api/graph/badge.svg?token=68Rpm1PpUr)](https://codecov.io/gh/ttskch/jp-postal-code-api)
[![](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml/badge.svg?branch=main)](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml?query=branch:main)
[![](https://github.com/ttskch/jp-postal-code-api/actions/workflows/pages/pages-build-deployment/badge.svg?branch=main)](https://github.com/ttskch/jp-postal-code-api/actions/workflows/pages/pages-build-deployment?query=branch:main)

この郵便番号APIは、[日本郵便によって公開されているデータ](https://www.post.japanpost.jp/zipcode/download.html) を元に、GitHub Pagesを使用して静的なJSONファイルとして配信しています。そのため可用性が高く、またオープンソースなのでクライアントワークでも安心して使用できます。

郵便番号から英語の住所を取得することも可能です。ただし、大口事業所個別番号は英語には対応していません。

なお、このAPIの配信データはGitHub Actionsを使用して [毎日最新の内容に更新しています](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml?query=branch:main)。

> [!NOTE]
> このAPIの実装は [madefor/postal-code-api](https://github.com/madefor/postal-code-api) にインスピレーションを受けています。長期間メンテナンスが行われていない同リポジトリに代わるものとして、モダンPHPで再実装しました。オリジナルのソースコードに最大の敬意を表します。

## エンドポイント

```
https://jp-postal-code-api.ttskch.com/api/
```

# 使い方

郵便番号が `100-0014` の住所（東京都千代田区永田町）を取得したい場合。

https://jp-postal-code-api.ttskch.com/api/1000014.json

```json
{
    "postalCode": "1000014",
    "addresses": [
        {
            "prefectureCode": "13",
            "ja": {
                "prefecture": "東京都",
                "address1": "千代田区",
                "address2": "永田町",
                "address3": "",
                "address4": ""
            },
            "en": {
                "prefecture": "Tokyo",
                "address1": "Chiyoda-ku",
                "address2": "Nagatacho ",
                "address3": "",
                "address4": ""
            }
        }
    ]
}
```

1つの郵便番号に複数の住所がある場合。

https://jp-postal-code-api.ttskch.com/api/6180000.json

```json
{
    "postalCode": "6180000",
    "addresses": [
        {
            "prefectureCode": "26",
            "ja": {
                "prefecture": "京都府",
                "address1": "乙訓郡大山崎町",
                "address2": "",
                "address3": "",
                "address4": ""
            },
            "en": {
                "prefecture": "Kyoto",
                "address1": "Oyamazaki-cho, Otokuni-gun",
                "address2": "",
                "address3": "",
                "address4": ""
            }
        },
        {
            "prefectureCode": "27",
            "ja": {
                "prefecture": "大阪府",
                "address1": "三島郡島本町",
                "address2": "",
                "address3": "",
                "address4": ""
            },
            "en": {
                "prefecture": "Osaka",
                "address1": "Shimamoto-cho, Mishima-gun",
                "address2": "",
                "address3": "",
                "address4": ""
            }
        }
    ]
}
```

大口事業所個別番号では英語の住所は空になります。

https://jp-postal-code-api.ttskch.com/api/1008111.json

```json
{
    "postalCode": "1008111",
    "addresses": [
        {
            "prefectureCode": "13",
            "ja": {
                "prefecture": "東京都",
                "address1": "千代田区",
                "address2": "千代田",
                "address3": "１−１",
                "address4": "宮内庁"
            },
            "en": {
                "prefecture": "",
                "address1": "",
                "address2": "",
                "address3": "",
                "address4": ""
            }
        }
    ]
}
```

## APIの自動更新

[こちらの](.github/workflows/cron.yaml) GitHub Actions Workflowによって、[毎日午前0時頃に自動的に](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml?query=branch:main) APIの配信データの内容を最新化しています。

更新処理の具体的な内容は以下のとおりです。

1. 日本郵便のWebサイトから [住所の郵便番号](https://www.post.japanpost.jp/zipcode/dl/roman-zip.html) と [事業所の個別郵便番号](https://www.post.japanpost.jp/zipcode/dl/jigyosyo/index-zip.html) のデータをダウンロード
2. ダウンロードしたZipファイルからCSVファイルを取得
3. CSVファイルをパースし、配信データとしてのJSONファイル群を生成

## ローカル環境での使用

```shell
# インストール
$ git clone git@github.com:ttskch/jp-postal-code-api.git
$ cd jp-postal-code-api
$ composer install
```

```shell
# docs/api/ 配下にJSONファイルを生成（15万ファイルほど生成されるので要注意）
$ bin/console build
```

## 貢献

* バグの報告や機能の提案は [Issue](https://github.com/ttskch/jp-postal-code-api/issues) または [Pull Request](https://github.com/ttskch/jp-postal-code-api/pulls) にてお願いします
* Starを付けていただけると開発者のモチベーションが上がります

## ライセンス

[MIT](LICENSE)
