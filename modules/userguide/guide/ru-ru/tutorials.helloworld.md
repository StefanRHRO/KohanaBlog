# Hello, World

Каждый фреймворк располагает примером написания приложения hello world, так что не будем нарушать традицию!

Мы начнем с создания простейшего hello world, а затем расширим его согласно принципам MVC.

## Основа

Сперва надо создать контроллер, который Kohana будет использовать для обработки запроса

Создайте файл `application/classes/controller/hello.php` в директории application и вставьте туда такой текст:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller
	{
		function action_index()
		{
			echo 'hello, world!';
		}
	}

Давайте разберемся, что там происходит:

`<?php defined('SYSPATH') OR die('No Direct Script Access');`
:	В первом тэге Вы наверняка узнали открывающий тэг php (иначе советуем сперва [изучить php](http://php.net)).  Следующая за ним строчка осуществляет проверку, был ли данный файл загружен в Kohana. Это закроет доступ к файлам проекта напрямую.

`Class Controller_Hello extends Controller`
:	Эта строка объявляет наш контроллер, каждый класс контроллера начинается с префикса `Controller_`, где знак подчеркивания является разделителем для пути к папке, в которой лежит файл контроллера (см. [Соглашения и стили](start.conventions)).  Каждый контроллер должен также быть потомком базового класса `Controller`.

`function action_index()`
:	Объявляем "индексный" метод нашего контроллера.  Kohana будет пытаться выполнить данный метод, если пользователь явно не укажет другой. (см. [Маршруты, URL и ссылки](tutorials.urls))

`echo 'hello, world!';`
:	Эта строка выводит стандартную фразу!

Теперь откроем браузер и, введя URL http://your/kohana/website/index.php/hello, увидим результат:

![Hello, World!](img/hello_world_1.png "Hello, World!")

## Все хорошо, но может быть и получше

Все проделанное в предыдущей секции было хорошим примером того, как легко создать *предельно* простое приложение на Kohana (на самом деле оно настолько простое, что Вам больше не придется создавать подобное!)

Если Вы что-нибудь слышали про MVC, то скорее всего представляете, что вывод результата в контроллере противоречит принципам MVC.

Более грамотный способ работать с MVC-фреймворками - это использование _представлений_ для отображения приложения, и позволить контроллеру делать то, с чем он справится лучше всего - контролировать ход выполнения запроса!

Давайте слегка поменяем наш контроллер:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller_Template
	{
		public $template = 'site';

		function action_index()
		{
			$this->template->message = 'hello, world!';
		}
	}

`extends Controller_Template`
:	Теперь мы расширяем шаблонный контроллер (template controller),  что делает работу контроллера с представлениями более удобной.

`public $template = 'site';`
:	Шаблонный контроллер должен знать, какое представление использовать. Он автоматически загрузит указанное представление в данную переменную в виде объекта.

`$this->template->message = 'hello, world!';`
:	`$this->template` является ссылкой на наш шаблон.  Мы присваиваем переменной "message" значение "hello, world!", и добавляем ее в шаблон template.

А теперь попробуем выполнить наш код...

<div>{{userguide/examples/hello_world_error}}</div>

По каким-то причинам kohana генерирует ошибку и не хочет показать наше восхитительное сообщение.

Если мы посмотрим на сообщение с ошибкой, то увидим, что библиотека View не смогла найти наш главный шаблон, скорее всего потому что мы его еще не создали - *черт*!

Давайте добавим файл представления `application/views/site.php` для нашего сообщения:

	<html>
		<head>
			<title>We've got a message for you!</title>
			<style type="text/css">
				body {font-family: Georgia;}
				h1 {font-style: italic;}

			</style>
		</head>
		<body>
			<h1><?php echo $message; ?></h1>
			<p>We just wanted to say it! :)</p>
		</body>
	</html>

Если обновить страницу, то мы увидим увидим результаты наших усилий:

![hello, world! Мы просто хотели это произнести!](img/hello_world_2.png "hello, world! Мы просто хотели это произнести!")

## Этап 3 - Итого!

В данной статье Вы изучили, как создать контроллер и использовать шаблоны для отделения логики от представления.

Очевидно, что это было всего-навсего упрощенное вступление, и оно не отражает даже малой части всех возможностей, доступных при разработке приложений с помощью kohana.
