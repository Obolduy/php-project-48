### Hexlet tests and linter status:
[![Actions Status](https://github.com/Obolduy/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/Obolduy/php-project-48/actions)
[![CI](https://github.com/Obolduy/php-project-48/actions/workflows/ci.yml/badge.svg)](https://github.com/Obolduy/php-project-48/actions/workflows/ci.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Obolduy_php-project-48&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=Obolduy_php-project-48)

## Описание
Консольное приложение для вычисления отличий между файлами. На текущий моменнт есть поддержка yml и json файлов.

## Installation
```bash
git clone https://github.com/Obolduy/php-project-48.git

cd php-project-48

make install
```

## Использование
```bash
bin/gendiff <firstFile> <secondFile>

bin/gendiff --format <format> <firstFile> <secondFile>
```

### Доступные форматы:
- `stylish` (по-умолчанию) - древовидная структура
- `plain` - plain-текст
- `json` - JSON

### Examples:
```bash
bin/gendiff file1.json file2.json

bin/gendiff --format plain file1.yml file2.yml

bin/gendiff --format json file1.json file2.json
```

## Запуск тестов и линтера
```bash
# Run tests
make test

# Run linter
make lint

# Run tests with coverage
make test-coverage
```

### Asciinemas

#### Flat JSON
https://asciinema.org/a/hr9IMBzp6X8FLVBbocIwxLUkR

#### Flat YML
https://asciinema.org/a/x2WaqbdLRFN0pCW72SjQnBi3j

#### Nested YML
https://asciinema.org/a/XS9Mv5gdTs63RYeIQ18VSS8PS

#### Plain format YML
https://asciinema.org/a/NAKjoRxtPCJIToNZnsq2ySwZo

#### JSON format YML
https://asciinema.org/a/E1KJGK5xcDnRUF5TGlen00dQd
