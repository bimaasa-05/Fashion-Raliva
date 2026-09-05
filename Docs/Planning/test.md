ada beberapa list revisi nih buat kita perbaiki yang pertama admin pada halaman laporan Operasional itu terdapat error seperti ini "# BadMethodCallException - Internal Server Error

Call to undefined method App\Models\Checkout::order()

PHP 8.2.31
Laravel 12.67.0
127.0.0.1:8000

## Stack Trace

0 - vendor\laravel\framework\src\Illuminate\Support\Traits\ForwardsCalls.php:67
1 - vendor\laravel\framework\src\Illuminate\Support\Traits\ForwardsCalls.php:36
2 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:2544
3 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:1124
4 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\Relation.php:119
5 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:1123
6 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:46
7 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:114
8 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php:1604
9 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:68
10 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:117
11 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:43
12 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Concerns\QueriesRelationships.php:172
13 - vendor\laravel\framework\src\Illuminate\Support\Traits\ForwardsCalls.php:23
14 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:2544
15 - vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php:2560
16 - app\Http\Controllers\Admin\LaporanController.php:32
17 - vendor\laravel\framework\src\Illuminate\Routing\ControllerDispatcher.php:46
18 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:265
19 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:211
20 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:822
21 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
22 - app\Http\Middleware\EnsureRole.php:29
23 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
24 - app\Http\Middleware\SetLocale.php:21
25 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
26 - vendor\laravel\framework\src\Illuminate\Routing\Middleware\SubstituteBindings.php:50
27 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
28 - vendor\laravel\framework\src\Illuminate\Session\Middleware\AuthenticateSession.php:70
29 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
30 - vendor\laravel\framework\src\Illuminate\Auth\Middleware\Authenticate.php:63
31 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
32 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken.php:87
33 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
34 - vendor\laravel\framework\src\Illuminate\View\Middleware\ShareErrorsFromSession.php:48
35 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
36 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:120
37 - vendor\laravel\framework\src\Illuminate\Session\Middleware\StartSession.php:63
38 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
39 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse.php:36
40 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
41 - vendor\laravel\framework\src\Illuminate\Cookie\Middleware\EncryptCookies.php:74
42 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
43 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
44 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:821
45 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
46 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
47 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
48 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
49 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
50 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
51 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
52 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
53 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
54 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
55 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
56 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
57 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
58 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
59 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
60 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:61
61 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
62 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
63 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
64 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
65 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
66 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
67 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
68 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
69 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
70 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
71 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
72 - public\index.php:20
73 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

GET /admin/laporan

## Headers

* **host**: 127.0.0.1:8000
* **connection**: keep-alive
* **sec-ch-ua**: "Google Chrome";v="149", "Chromium";v="149", "Not)A;Brand";v="24"
* **sec-ch-ua-mobile**: ?0
* **sec-ch-ua-platform**: "Windows"
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: navigate
* **sec-fetch-user**: ?1
* **sec-fetch-dest**: document
* **referer**: http://127.0.0.1:8000/admin/riwayat-aktivitas
* **accept-encoding**: gzip, deflate, br, zstd
* **accept-language**: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7
* **cookie**: XSRF-TOKEN=eyJpdiI6IlpVdmRiVGNYNVVNT3VWb2FUSWRSN0E9PSIsInZhbHVlIjoiYm13TFg2RmZBbFZpTzNsN2JCRDJ1YWV3WXBYSy9OK1ExOHpudEpZdUVOblE0a3VEWjVkVWxXdnkzOVZtb1FlRkFDanJnN1dJZzYvR0s2RHBQWityc1FnV211Y3BacmdFOEJYeDZsbkdDOXRBNWtRRERDcWFlcGQwWmtjRXRHT3oiLCJtYWMiOiJhNDI0NDQ3YmI5OGY4N2M2ZmMwN2JkZmYwZTRmOWQ2N2I3MWRiMGIwZjQ0OTU0MzM2N2JjMmFhYjZmMTlkOWI2IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IkNoUUN0cjVkWkZmZVRxNXdDYjRwS0E9PSIsInZhbHVlIjoicHViWHFEeHQ4VGxLd2paOXUvK0hITUI0VGNMYUhFOGVEVzc5cEEwMlc1SDhJSWcvRFphNlgxQ3Ardi91SWo5S0g3d1NzemZJUnBiKzBxTFBCMjJvWkVRaTAweEkyaDBldTgxbzZmdmR3T1dMaU9GL1J3bFJBb0Y0UUU1OW9hL08iLCJtYWMiOiIxYjI2NmEzNDE2YTgxZDMyODdkYzg0M2U3MjRiZDUzMzRkOTk5MzYzMDdlYzRkMGE0ZjkxYjc4NTdkNDdkYzNiIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\Admin\LaporanController@index
route name: admin.laporan
middleware: web, auth, role:Admin

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `sessions` where `id` = '3nJ2HVDCOINMg7uETWsLfQHHtrdy8AE7xiCDamTi' limit 1 (12.14 ms)
* mysql - select * from `users` where `user_id` = 14 limit 1 (2.64 ms)
* mysql - select * from `roles` where `roles`.`role_id` = 3 limit 1 (2 ms)
* mysql - select `store_id` from `store_staff` where `user_id` = 14 and `status` = 'aktif' (2.7 ms)
* mysql - select sum(`grand_total`) as aggregate from `orders` where `store_id` in (3) and `status` = 'selesai' (3.59 ms)
* mysql - select count(*) as aggregate from `orders` where `store_id` in (3) and `status` = 'selesai' (0.7 ms)
* mysql - select sum(`refunds`.`jumlah`) as aggregate from `refunds` inner join `orders` on `orders`.`order_id` = `refunds`.`order_id` where `orders`.`store_id` in (3) and `refunds`.`status` = 'selesai' (10.41 ms)
* mysql - select sum(`nominal`) as aggregate from `store_expenses` where `store_id` in (3) (5.59 ms)
* mysql - select count(*) as aggregate from `orders` where `store_id` in (3) and `status` in ('pending_payment', 'dibayar') (0.61 ms) "