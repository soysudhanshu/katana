@props(['file', 'lineNo'])
<div class="stacktrace">
    <span class="file">
        {{ $file }}:{{ $lineNo }}
    </span>
    <span class="function">
        {{ $class }}\{{ $function }}
    </span>
</div>
