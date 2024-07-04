# Katana

Allows you to library provides functionality to render Laravel Blade templates in any PHP project, without requiring the full Laravel framework.

> **Note:** This package is still in development and not ready for production use.

## Getting Starting

You can install the package via composer:

```bash
composer require soysudhanshu/katana
```

### Usage

```php

use Blade\Blade;

$blade = new Blade([
    'views' => __DIR__ . '/views',
    'cache' => __DIR__ . '/cache',
]);

echo $blade->render('hello', ['name' => 'Jhon Doe']);
```

## Feature List
### Blade Directives
| Directive | Description | Status
| --- | --- | --- |
|  `{{ $var }}` | Display the value of the variable | ✅ |
| `{!! $var !!}` | Display the value of the variable without escaping | ✅ |
| `@{{ $var }}` | Escaping blade directive | ✅ |
| `@{!! $var !!}` | Escaping unsafe output directive | ✅ |
| `@@<any>` | Escaping control blade directive | ✅ |
| `@verbatim` | Prevents rendering. Unlike original blade we also support nested verbatims! | ✅ |


### Control Structures
| Directive | Description | Status
| --- | --- | --- |
| `@if` | If statement | ✅ |
| `@unless` | Convenient if | ✅ |
| `@isset` | Checks if variable is set | ✅ |
| `@empty` | Check if the variable is empty | ✅ |
| `@switch` | Switch statement | ✅ |
| `@case` | Case statement | ✅ |
| `@default` | Default statement | ✅ |
| `@break` | Break statement | ✅ |
| `@continue` | Continue statement | ✅ |

### Loops
| Directive | Description | Status
| --- | --- | --- |
| `@for` | For loop | ✅ |
| `@foreach` | Foreach loop | ✅ |
| `@while` | While loop | ❌ |
| `@forelse` | Forelse loop | ❌ |
| `$loop` | Loop variable in the for loop (basic) | ✅ |

### Conditional Class & Styles
| Directive | Description | Status
| --- | --- | --- |
| `@class` | Conditional class | ✅ |
| `@style` | Conditional style | ❌ |

### Components
| Directive | Description | Status
|--|--|--|
|`@component`|  | ❌
| `Class components` |  | ❌
|`Anonymous component`|   | ✅
|`Vendor Namespacing`|  | ❌
|`Component Attributes`|  | ✅
|`Short hand attribute syntax`|  | ❌
|`Attribute Render Escaping`|  | ❌
|`Component Methods`|  | ❌
|`{{ $attributes }}`|  | ✅
|`{{ $attributes }}`|  | ✅
|`{{ $attributes->merge() }}`|  | ✅
|`{{ $attributes->class() }}`|  | ✅
|`{{ $attributes->class() }} Conditional`|  | ✅
|`{{ $attributes->prepends() }}`|  | ❌
|`{{ $attributes->filter() }}`|  | ❌
|`{{ $attributes->whereStartsWith() }}`|  | ❌
|`{{ $attributes->whereDoesntStartWith() }}`|  | ❌
|`{{ $attributes->whereDoesntStartWith()->first() }}`|  | ❌
|`{{ $attributes->has() }}`|  | ❌
|`{{ $attributes->hasAny() }}`|  | ❌
|`{{ $attributes->get() }}`|  | ✅
|`Default {{ $slot }}`|  | ✅
|`Name slots {{ $customSlot }}`|  | ❌
|`$slot->isEmpty()`|  | ❌
|`$slot->hasActualContent()`|  | ❌
|`$slot->isEmpty()`|  | ❌
|`$slot->isEmpty()`|  | ❌
|`Scoped Slots`|  | ❌
|`Slot Attributes`|  | ❌
|`Dynamic Components`|  | ❌
|`Anonymous Index Components`|  | ❌


### Templating
| Directive | Description | Status
|--|--|--|
| `@auth` | | ❌
| `@guest` | | ❌
| `@production` | | ❌
| `@env` | | ❌
| `@hasSection` | | ❌
| `@sectionMissing` | | ❌
| `@include` | | ❌
| `@session` | | ❌
| `@checked` | | ❌
| `@disabled` | | ❌
| `@readonly` | | ❌
| `@required` | | ❌
| `@includeIf` | | ❌
| `@includeWhen` | | ❌
| `@includeUnless` | | ❌
| `@includeFirst` | | ❌
| `@each` | | ❌
| `@once` | | ❌
| `@push` | | ❌
| `@pushOnce` | | ❌
| `@prependOnce` | | ❌
| `@php` | | ✅
| `@use` | | ❌
