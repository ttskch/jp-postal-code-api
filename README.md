# 日本の郵便番号API

[![](https://github.com/ttskch/jp-postal-code-api/actions/workflows/ci.yaml/badge.svg?branch=main)](https://github.com/ttskch/jp-postal-code-api/actions/workflows/ci.yaml?query=branch:main)
[![codecov](https://codecov.io/gh/ttskch/jp-postal-code-api/graph/badge.svg?token=68Rpm1PpUr)](https://codecov.io/gh/ttskch/jp-postal-code-api)
[![](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml/badge.svg?branch=main)](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml?query=branch:main)
[![](https://github.com/ttskch/jp-postal-code-api/actions/workflows/pages/pages-build-deployment/badge.svg?branch=main)](https://github.com/ttskch/jp-postal-code-api/actions/workflows/pages/pages-build-deployment?query=branch:main)

日本の郵便番号から住所のデータを取得できるWeb APIです。

GitHub Pagesを使用して静的なJSONファイルとして配信しているため、可用性が高いのが特徴です。また、オープンソースなのでクライアントワークでも安心してご使用いただけます。もしリポジトリの永続性や [GitHub Pagesの利用制限](#github-pagesの利用制限について) が心配な場合は、ご自由にフォークしてご利用ください。

[日本郵便によって公開されているデータ](https://www.post.japanpost.jp/zipcode/download.html) を元に住所データのJSONファイルを生成して配信しています。配信データはGitHub Actionsを使用して [毎日最新の内容に更新しています](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml?query=branch:main)。

> [!NOTE]
> このプロジェクトの実装は [madefor/postal-code-api](https://github.com/madefor/postal-code-api) にインスピレーションを受けています。長期間メンテナンスが行われていない同プロジェクトに代わるものとして、モダンPHPで再実装しました。オリジナルのソースコードに最大の敬意を表します。

## デモ

https://jp-postal-code-api.ttskch.com

## エンドポイント

```
https://jp-postal-code-api.ttskch.com/api/v1/{郵便番号}.json
```

## 使い方

例えば、郵便番号が `100-0014` の住所（東京都千代田区永田町）を取得したい場合は、エンドポイントのURLとレスポンスの内容は以下のようになります。

https://jp-postal-code-api.ttskch.com/api/v1/1000014.json

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
            "kana": {
                "prefecture": "トウキョウト",
                "address1": "チヨダク",
                "address2": "ナガタチョウ",
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

1つの郵便番号に複数の住所がある場合は、レスポンスの内容は以下のようになります。

https://jp-postal-code-api.ttskch.com/api/v1/6180000.json

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
            "kana": {
                "prefecture": "キョウトフ",
                "address1": "オトクニグンオオヤマザキチョウ",
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
            "kana": {
                "prefecture": "オオサカフ",
                "address1": "ミシマグンシマモトチョウ",
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

大口事業所個別番号では、事業所名以外のカナ表記と英語表記は空になります。


https://jp-postal-code-api.ttskch.com/api/v1/1008111.json

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
            "kana": {
                "prefecture": "",
                "address1": "",
                "address2": "",
                "address3": "",
                "address4": "クナイチヨウ"
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

[2024年1月1日に市町村変更があった住所](https://www.post.japanpost.jp/zipcode/merge/index.html) を取得すると、2024年5月現在では英語表記は出力されません。[元データ](https://www.post.japanpost.jp/zipcode/dl/roman-zip.html) が更新されると、このWeb APIの配信データも最大1日の誤差で自動的に更新されます。

https://jp-postal-code-api.ttskch.com/api/v1/4328003.json

```json
{
    "postalCode": "4328003",
    "addresses": [
        {
            "prefectureCode": "22",
            "ja": {
                "prefecture": "静岡県",
                "address1": "浜松市中央区",
                "address2": "和地山",
                "address3": "",
                "address4": ""
            },
            "kana": {
                "prefecture": "シズオカケン",
                "address1": "ハママツシチュウオウク",
                "address2": "ワジヤマ",
                "address3": "",
                "address4": ""
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

## 配信データの仕様

Web APIの配信データは [日本郵便によって公開されているデータ](https://www.post.japanpost.jp/zipcode/download.html) を元に生成しています。具体的なスキーマは [使い方](#使い方) の例をご参照ください。 日本語表記・カナ表記・英語表記の住所データが含まれていますが、後述の制限事項があります。

### 制限事項

* 大口事業所個別番号の住所データは以下のように出力されます（[元データ](https://www.post.japanpost.jp/zipcode/dl/jigyosyo/index-zip.html) の内容がそうであるため）
    * カナ表記は事業所名についてのみ出力されます
    * 事業所名のカナ表記は促音・拗音が大書きで出力されます
    * 英語表記は出力されません
* 一部の住所において、英語表記のうち主に `address2` フィールドの内容が途切れている場合があります（[元データ](https://www.post.japanpost.jp/zipcode/dl/roman-zip.html) の内容が [そうである](https://www.post.japanpost.jp/zipcode/dl/readme_ro.html#:~:text=%E5%8D%8A%E8%A7%92%E3%81%A8%E3%81%AA%E3%81%A3%E3%81%A6%E3%81%84%E3%82%8B%E3%83%AD%E3%83%BC%E3%83%9E%E5%AD%97%E9%83%A8%E5%88%86%E3%81%AE%E6%96%87%E5%AD%97%E6%95%B0%E3%81%8C35%E6%96%87%E5%AD%97%E3%82%92%E8%B6%85%E3%81%88%E3%82%8B%E5%A0%B4%E5%90%88%E3%81%AF%E3%80%81%E8%B6%85%E3%81%88%E3%81%9F%E9%83%A8%E5%88%86%E3%81%AE%E5%8F%8E%E9%8C%B2%E3%81%AF%E8%A1%8C%E3%81%A3%E3%81%A6%E3%81%8A%E3%82%8A%E3%81%BE%E3%81%9B%E3%82%93%E3%80%82) ため）
* 直近1年程度以内に [市町村変更があった住所](https://www.post.japanpost.jp/zipcode/merge/index.html) については、英語表記は出力されません（[元データが年1回程度しか更新されない](https://www.post.japanpost.jp/zipcode/dl/roman-zip.html) ため）

### 自動更新

[こちらのGitHub Actions Workflow](.github/workflows/cron.yaml) によって、[毎日午前0時頃に自動的に](https://github.com/ttskch/jp-postal-code-api/actions/workflows/cron.yaml?query=branch:main) Web APIの配信データの内容を最新化しています。更新処理の具体的な内容は以下のとおりです。

1. [日本郵便のWebサイト](https://www.post.japanpost.jp/zipcode/download.html) から [住所の郵便番号](https://www.post.japanpost.jp/zipcode/dl/kogaki-zip.html)、[住所の郵便番号（ローマ字）](https://www.post.japanpost.jp/zipcode/dl/roman-zip.html)、[事業所の個別郵便番号](https://www.post.japanpost.jp/zipcode/dl/jigyosyo/index-zip.html) のデータをダウンロード
2. ダウンロードしたZipファイルからCSVファイルを取得
3. CSVファイルをパースし、配信データとしてのJSONファイル群を生成
4. その際、「住所の郵便番号」と「住所の郵便番号（ローマ字）」のデータを、日本語表記の住所が一致している場合にのみマージ
5. 生成したJSONファイル群をコミットし、GitHub Pagesを更新

## GitHub Pagesの利用制限について

2024年5月現在、GitHub Pagesで公開したサイトには [月当たり100GBの帯域制限](https://docs.github.com/ja/pages/getting-started-with-github-pages/about-github-pages#:~:text=GitHub%20Pages%20%E3%82%B5%E3%82%A4%E3%83%88%E3%81%AB%E3%81%AF%E3%80%81%E6%9C%88%E5%BD%93%E3%81%9F%E3%82%8A%20100%20GB%20%E3%81%AE%20%E3%82%BD%E3%83%95%E3%83%88%E3%81%AA%20%E5%B8%AF%E5%9F%9F%E5%B9%85%E5%88%B6%E9%99%90%E3%81%8C%E3%81%82%E3%82%8A%E3%81%BE%E3%81%99%E3%80%82) があります。このWeb APIの配信データの容量は平均およそ400バイトなので、毎秒104リクエスト程度のペースが1ヶ月間継続すると制限の対象となる可能性があります。

もしこの制限が心配な場合は、本リポジトリをフォークしてご自身のGitHubアカウントでホストしてご利用ください。その場合、エンドポイントのURLは

```
https://{あなたのGitHubユーザー名}.github.io/jp-postal-code-api/api/v1/{郵便番号}.json
```

のようになります。

ただし、それでも悪意ある攻撃者によって大量のリクエストが行われると利用制限の対象になる可能性があります。どうしても心配な場合は、フォークしたリポジトリを [Cloudflare Pages](https://www.cloudflare.com/ja-jp/developer-platform/pages/) などの多機能なホスティングサービスやその他PaaSなどに接続して、BASIC認証などをかけた状態でWeb APIをホストするといった運用を検討してください。

## ローカル環境での使用

```shell
# インストール
$ git clone git@github.com:ttskch/jp-postal-code-api.git
$ cd jp-postal-code-api
$ composer install
```

```shell
# docs/api/v1/ 配下にJSONファイルを生成（15万ファイルほど生成されるので要注意）
$ bin/console build
```

## 貢献

* バグの報告や機能の提案は [Issue](https://github.com/ttskch/jp-postal-code-api/issues) または [Pull Request](https://github.com/ttskch/jp-postal-code-api/pulls) にてお願いします
* Starを付けていただけると開発者のモチベーションが上がります

## ライセンス

[MIT](LICENSE)

> [!NOTE]
> 日本郵便株式会社は、[郵便番号データ](https://www.post.japanpost.jp/zipcode/dl/readme.html#:~:text=%E9%83%B5%E4%BE%BF%E7%95%AA%E5%8F%B7%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AB%E9%99%90%E3%81%A3%E3%81%A6%E3%81%AF%E6%97%A5%E6%9C%AC%E9%83%B5%E4%BE%BF%E6%A0%AA%E5%BC%8F%E4%BC%9A%E7%A4%BE%E3%81%AF%E8%91%97%E4%BD%9C%E6%A8%A9%E3%82%92%E4%B8%BB%E5%BC%B5%E3%81%97%E3%81%BE%E3%81%9B%E3%82%93%E3%80%82%E8%87%AA%E7%94%B1%E3%81%AB%E9%85%8D%E5%B8%83%E3%81%97%E3%81%A6%E3%81%84%E3%81%9F%E3%81%A0%E3%81%84%E3%81%A6%E7%B5%90%E6%A7%8B%E3%81%A7%E3%81%99%E3%80%82)、[郵便番号データ（ローマ字）](https://www.post.japanpost.jp/zipcode/dl/readme_ro.html#:~:text=%E9%83%B5%E4%BE%BF%E7%95%AA%E5%8F%B7%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AB%E9%99%90%E3%81%A3%E3%81%A6%E3%81%AF%E6%97%A5%E6%9C%AC%E9%83%B5%E4%BE%BF%E6%A0%AA%E5%BC%8F%E4%BC%9A%E7%A4%BE%E3%81%AF%E8%91%97%E4%BD%9C%E6%A8%A9%E3%82%92%E4%B8%BB%E5%BC%B5%E3%81%97%E3%81%BE%E3%81%9B%E3%82%93%E3%80%82%E8%87%AA%E7%94%B1%E3%81%AB%E9%85%8D%E5%B8%83%E3%81%97%E3%81%A6%E3%81%84%E3%81%9F%E3%81%A0%E3%81%84%E3%81%A6%E7%B5%90%E6%A7%8B%E3%81%A7%E3%81%99%E3%80%82)、および [大口事業所個別番号データ](https://www.post.japanpost.jp/zipcode/dl/jigyosyo/readme.html#:~:text=%E5%A4%A7%E5%8F%A3%E4%BA%8B%E6%A5%AD%E6%89%80%E5%80%8B%E5%88%A5%E7%95%AA%E5%8F%B7%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AB%E9%99%90%E3%81%A3%E3%81%A6%E3%81%AF%E6%97%A5%E6%9C%AC%E9%83%B5%E4%BE%BF%E6%A0%AA%E5%BC%8F%E4%BC%9A%E7%A4%BE%E3%81%AF%E8%91%97%E4%BD%9C%E6%A8%A9%E3%82%92%E4%B8%BB%E5%BC%B5%E3%81%97%E3%81%BE%E3%81%9B%E3%82%93%E3%80%82%E8%87%AA%E7%94%B1%E3%81%AB%E9%85%8D%E5%B8%83%E3%81%97%E3%81%A6%E3%81%84%E3%81%9F%E3%81%A0%E3%81%84%E3%81%A6%E7%B5%90%E6%A7%8B%E3%81%A7%E3%81%99%E3%80%82%E6%97%A5%E6%9C%AC%E9%83%B5%E4%BE%BF%E6%A0%AA%E5%BC%8F%E4%BC%9A%E7%A4%BE%E3%81%B8%E3%81%AE%E8%A8%B1%E8%AB%BE%E3%82%82%E5%BF%85%E8%A6%81%E3%81%82%E3%82%8A%E3%81%BE%E3%81%9B%E3%82%93%E3%80%82) について著作権を主張していません。
