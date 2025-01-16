# Katana

[![Tests](https://github.com/soysudhanshu/katana/actions/workflows/tests.yml/badge.svg)](https://github.com/soysudhanshu/katana/actions/workflows/tests.yml)

Katana is a lightweight library that enables you to render Laravel Blade templates in any PHP project, without needing the full Laravel framework. Katana encourages you to use new component syntax, however traditional Blade syntax is also supported for porting from other projects like BladeOne.

Katana uses `.blade.php` extension for templates, traditionally stored in `/views` directory but can be configured.

## Comparison
| Feature | Katana | BladeOne |
| --- | --- | --- |
| Layouts Components | ✅ | 🟨 |
| Layouts Template Inheritance | ✅ | ✅ |
| Control Structures | ✅ | ✅ |
| Loops | ✅ | ✅ |
| Conditional Class & Styles | ✅ | ❌ |
| Additional Form Attributes | ✅ | ❌ |
| Pipes | ❌ | ✅ |




## Getting Starting

You can install the package via composer:

```bash
composer require soysudhanshu/katana
```

### Usage

```php

use Blade\Blade;

$blade = $blade = new Blade(__DIR__, __DIR__ . '/.cache');

echo $blade->render('hello', ['name' => 'Jhon Doe']);
```

## Layouts

### Template Inheritance - ✅

Template inheritance allows you to create layouts by defining a master template that can be extended by child templates.

```blade
{{-- layouts/app.blade.php --}}

<!DOCTYPE html>
<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        <main>
            @yield('content')
        </main>
        <aside>
            @yield('sidebar')
        </aside>
    </body>
</html>
```

```blade
{{-- blog-post.blade.php --}}

@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    <article>
        <h1>Blog Post</h1>
        <p>This is the blog post content.</p>
    </article>
@endsection

@section('sidebar')
    @parent

    <h3>Related Posts</h3>
    <ul>
        <li>Post 1</li>
        <li>Post 2</li>
        <li>Post 3</li>
    </ul>
@endsection
```

#### Supported Directives

| Directive         | Description                                    | Status |
| ----------------- | ---------------------------------------------- | ------ |
| `@extends`        | Directive to extend layout                     | ✅     |
| `@yield`          | Outputs a section content                      | ✅     |
| `@section`        | Defines a section content                      | ✅     |
| `@endsection`     | Defined a end of section content               | ✅     |
| `@show`           | Outputs a section content immediately          | ✅     |
| `@parent`         | Outputs the content of the parent section      | ✅     |
| `@hasSection`     | Determines if section content has been defined | ✅     |
| `@sectionMissing` | Determines if section content is missing       | ✅     |

## Feature List

### Blade Directives

| Directive       | Description                                        | Status |
| --------------- | -------------------------------------------------- | ------ |
| `{{ $var }}`    | Display the value of the variable                  | ✅     |
| `{!! $var !!}`  | Display the value of the variable without escaping | ✅     |
| `@{{ $var }}`   | Escaping blade directive                           | ✅     |
| `@{!! $var !!}` | Escaping unsafe output directive                   | ✅     |
| `@@<any>`       | Escaping control blade directive                   | ✅     |
| `@verbatim`     | Prevents rendering.                                | ✅     |

### Control Structures

| Directive   | Description                    | Status |
| ----------- | ------------------------------ | ------ |
| `@if`       | If statement                   | ✅     |
| `@unless`   | Convenient if                  | ✅     |
| `@isset`    | Checks if variable is set      | ✅     |
| `@empty`    | Check if the variable is empty | ✅     |
| `@switch`   | Switch statement               | ✅     |
| `@case`     | Case statement                 | ✅     |
| `@default`  | Default statement              | ✅     |
| `@break`    | Break statement                | ✅     |
| `@continue` | Continue statement             | ✅     |

### Loops

| Directive  | Description                           | Status |
| ---------- | ------------------------------------- | ------ |
| `@for`     | For loop                              | ✅     |
| `@foreach` | Foreach loop                          | ✅     |
| `@while`   | While loop                            | ✅     |
| `@forelse` | Forelse loop                          | ✅     |
| `$loop`    | Loop variable in the for loop (basic) | ✅     |

### Conditional Class & Styles

| Directive | Description       | Status |
| --------- | ----------------- | ------ |
| `@class`  | Conditional class | ✅     |
| `@style`  | Conditional style | ✅     |

### Components

| Directive                                            | Description | Status |
| ---------------------------------------------------- | ----------- | ------ |
| `@component`                                         |             | ❌     |
| `Class components`                                   |             | ❌     |
| `Anonymous component`                                |             | ✅     |
| `Vendor Namespacing`                                 |             | ❌     |
| `Component Attributes`                               |             | ✅     |
| `Short hand attribute syntax`                        |             | ❌     |
| `Attribute Render Escaping`                          |             | ❌     |
| `Component Methods`                                  |             | ❌     |
| `{{ $attributes }}`                                  |             | ✅     |
| `{{ $attributes->merge() }}`                         |             | ✅     |
| `{{ $attributes->class() }}`                         |             | ✅     |
| `{{ $attributes->class() }} Conditional`             |             | ✅     |
| `{{ $attributes->prepends() }}`                      |             | ✅     |
| `{{ $attributes->filter() }}`                        |             | ✅     |
| `{{ $attributes->whereStartsWith() }}`               |             | ✅     |
| `{{ $attributes->whereDoesntStartWith() }}`          |             | ✅     |
| `{{ $attributes->whereDoesntStartWith()->first() }}` |             | ✅     |
| `{{ $attributes->has() }}`                           |             | ✅     |
| `{{ $attributes->hasAny() }}`                        |             | ✅     |
| `{{ $attributes->get() }}`                           |             | ✅     |
| `Default {{ $slot }}`                                |             | ✅     |
| `Name slots {{ $customSlot }}`                       |             | ✅     |
| `$slot->isEmpty()`                                   |             | ✅     |
| `$slot->hasActualContent()`                          |             | ❌     |
| `Scoped Slots`                                       |             | ❌     |
| `Slot Attributes`                                    |             | ❌     |
| `Dynamic Components`                                 |             | ❌     |
| `Anonymous Index Components`                         |             | ❌     |

### Directives

| Directive        | Description | Status |
| ---------------- | ----------- | ------ |
| `@auth`          |             | ❌     |
| `@guest`         |             | ❌     |
| `@production`    |             | ❌     |
| `@env`           |             | ❌     |
| `@include`       |             | ✅     |
| `@session`       |             | ❌     |
| `@selected`      |             | ✅     |
| `@checked`       |             | ✅     |
| `@disabled`      |             | ✅     |
| `@readonly`      |             | ✅     |
| `@required`      |             | ✅     |
| `@includeIf`     |             | ✅     |
| `@includeWhen`   |             | ✅     |
| `@includeUnless` |             | ✅     |
| `@includeFirst`  |             | ✅     |
| `@each`          |             | ✅     |
| `@once`          |             | ✅     |
| `@push`          |             | ✅     |
| `@stack`         |             | ✅     |
| `@pushOnce`      |             | ❌     |
| `@prependOnce`   |             | ❌     |
| `@php`           |             | ✅     |
| `@use`           |             | ❌     |
