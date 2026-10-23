# Illuminate\Encryption\MissingAppKeyException - Internal Server Error

No application encryption key has been specified.

PHP 8.3.36
Laravel 13.7.0
bookingsystem-krms.onrender.com

## Stack Trace

0 - vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php:83
1 - vendor/laravel/framework/src/Illuminate/Support/helpers.php:393
2 - vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php:81
3 - vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php:64
4 - vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php:32
5 - vendor/laravel/framework/src/Illuminate/Container/Container.php:1118
6 - vendor/laravel/framework/src/Illuminate/Container/Container.php:936
7 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1078
8 - vendor/laravel/framework/src/Illuminate/Container/Container.php:864
9 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1058
10 - vendor/laravel/framework/src/Illuminate/Container/Container.php:1339
11 - vendor/laravel/framework/src/Illuminate/Container/Container.php:1240
12 - vendor/laravel/framework/src/Illuminate/Container/Container.php:1165
13 - vendor/laravel/framework/src/Illuminate/Container/Container.php:936
14 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1078
15 - vendor/laravel/framework/src/Illuminate/Container/Container.php:864
16 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1058
17 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:208
18 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
19 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
20 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
21 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
22 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
23 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
24 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
25 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
26 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:31
27 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
28 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php:21
29 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:51
30 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
31 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
32 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
33 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
34 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
35 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:61
36 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
37 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
38 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
39 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
40 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
41 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:28
42 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
43 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
44 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
45 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
46 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
47 - public/index.php:20
48 - vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php:23


## Request

GET /

## Headers

* **host**: bookingsystem-krms.onrender.com
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/jxl,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **accept-encoding**: gzip, br
* **accept-language**: ar,en-US;q=0.9,en;q=0.8
* **cdn-loop**: cloudflare; loops=1
* **cf-connecting-ip**: 176.224.188.86
* **cf-ipcountry**: SA
* **cf-ray**: a4edbb696d1be1e8-CMH
* **cf-visitor**: {"scheme":"https"}
* **priority**: u=0, i
* **render-proxy-ttl**: 4
* **rndr-id**: 72b247a6-9445-4a70
* **sec-ch-ua**: "Google Chrome";v="155", "Chromium";v="155", "Not(A:Brand";v="24"
* **sec-ch-ua-mobile**: ?0
* **sec-ch-ua-platform**: "Windows"
* **sec-fetch-dest**: document
* **sec-fetch-mode**: navigate
* **sec-fetch-site**: cross-site
* **sec-fetch-user**: ?1
* **true-client-ip**: 176.224.188.86
* **upgrade-insecure-requests**: 1
* **x-forwarded-for**: 176.224.188.86, 162.159.114.116, 10.26.9.133
* **x-forwarded-proto**: https
* **x-request-start**: 1792726949406522

## Route Context

controller: Closure
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

No database queries detected.