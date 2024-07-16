<div class="katana-error-container">
    <div class="error-message">
        <span class="error-type">
            {{ $errorType ?? 'Error' }}
        </span>
        <div class="message-text">
            {{ $message }}
        </div>
    </div>

    <div class="code-area">
        <div class="call-stack">
            @foreach ($stackTrace as $trace)
                {{-- @php dump($trace) @endphp --}}
                <x-stacktrace :file="$trace['file'] ?? ''"
                    :line-no="$trace['line'] ?? ''"
                    :class="$trace['class'] ?? ''"
                    :function="$trace['function'] ?? ''" />
            @endforeach
        </div>
        <div class="code-viewer-container" style="overflow: auto">
            <div class="error-line-info">
                <span>
                    {{ $file }}:{{ $errorLine }}
                </span>
            </div>
            <div class="code-viewer">
                <table>
                    @foreach ($lines as $lineNo => $line)
                    {{-- @php dd($lines) @endphp --}}
                        <tr @class([
                            'text-danger' => $lineNo == $errorLine,
                        ])>
                            <td>
                                {{ $lineNo }}
                            </td>
                            <td>
                                <pre>{{ $line }}</pre>
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
</div>

<style>
    .katana-error-container {

        font-family: ui-monospace,
            SFMono-Regular,
            Menlo,
            Monaco,
            Consolas,
            Liberation Mono,
            Courier New,
            monospace;

        display: grid;
        gap: 2rem;
        max-width: 1200px;
        margin: auto;
    }

    .error-type {
        background: #ff634752;
        padding: 0.5rem 1rem;
        border-radius: 10rem;
        display: inline-block;
        color: #ac2007;
        font-weight: bold;
    }

    .error-message,
    .code-area {
        padding: 1.5rem;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.10);
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: 2rem;
    }

    .code-viewer-container {
        display: grid;
        gap: 1.5rem;
    }

    .error-line-info {
        font-size: 0.75rem;
    }

    .message-text {
        font-weight: bold;
        font-size: 1.4rem;
    }

    .code-area {
        display: grid;
        grid-template-columns: 2fr 4fr;
    }

    .code-viewer {
        border: 1px solid #cccccc;
        padding: 1rem;
        border-radius: 0.5rem;
    }

    table {
        /*   table-layout: fixed; */
        width: 100%;
        max-width: 100%;
    }

    table,
    td,
    tr {
        border-collapse: collapse
    }

    td {
        padding: 0.25rem;
        font-weight: 400;
    }

    .code-viewer td:first-child {
        /*   border-right:1px solid #cccccc; */
        width: 3ch;
        text-align: center;
    }

    .text-danger,
    tr:hover {
        background: rgba(255, 0, 0, 0.3);
    }

    .call-stack {
        overflow: hidden;
    }

    .stacktrace {
        background: rgba(0, 0, 0, 0.0.5);
        border-left: 2px solid red;
        padding: 0.75rem;
    }

    .stacktrace .file {
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        max-width: 100%;
        display: inline-block;
    }
</style>
