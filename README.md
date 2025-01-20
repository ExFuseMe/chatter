# Тестовый проект чата на Laravel 11

## Описание проекта
* Проект написан Никитиным Данилом
* Наличие веб-сокетов, реализованных на laravel/reverb
* Работа ивентов вне очереди(более быстрая отправка сообщений)
* Наличие вёрстки
* Авторизация через jetstream+fortify

## Требования
* php 8.4
* node + npm
* docker

## Развертка проекта
```bash
composer i
vendor/bin/sail up -d
npm i
npm run dev
#после уже можно выполнить npm run build
```
после выполнения команд выше ^:
```bash
vendor/bin/sail reverb:start
vendor/bin/sail artisan migrate:fresh --seed
```
