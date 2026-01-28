<link rel="stylesheet" href="{{ asset('vendor/flowsense/css/flowsense-toolbar.css') }}">

<div id="flowsense-toolbar">
    <div class="fs-header">
        <div class="fs-toggle" id="fs-toggle">▲</div>
        <div class="fs-tab active" data-tab="route">Route</div>
        <div class="fs-tab" data-tab="queries">Queries ({{ $queries['count'] }})</div>
        <div class="fs-tab" data-tab="performance">Performance</div>
        <div class="fs-tab" data-tab="request">Request</div>
        <div class="fs-tab" data-tab="views">Views</div>
        <div class="fs-tab" data-tab="logs">Logs</div>
    </div>

    <!-- Route Tab -->
    <div class="fs-panel active" id="fs-panel-route">
        <p><strong>URI:</strong> {{ $route['route'] }}</p>
        <p><strong>Controller:</strong> {{ $route['controller'] }}</p>
        <p><strong>Method:</strong> {{ $route['method'] }}</p>
        <p><strong>Route Name:</strong> {{ $route['name'] ?? 'N/A' }}</p>
        <p><strong>Middleware:</strong> {{ implode(', ', $route['middleware'] ?? []) }}</p>
        <p><strong>Parameters:</strong> {{ json_encode($route['params'] ?? []) }}</p>
    </div>

    <!-- Queries Tab -->
    <div class="fs-panel" id="fs-panel-queries">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Time (s)</th>
                    <th>SQL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($queries['queries'] as $i => $q)
                    @php $isSlow = $q['time'] > 0.1; @endphp
                    <tr class="fs-query-row {{ $isSlow ? 'slow' : '' }}">
                        <td>{{ $i + 1 }}</td>
                        <td>{{ number_format($q['time'], 4) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($q['sql'], 80) }}</td>
                    </tr>
                    <tr class="fs-query-details">
                        <td colspan="3">
                            <pre>Bindings: {{ json_encode($q['bindings'], JSON_PRETTY_PRINT) }}</pre>
                            <pre>{{ $q['sql'] }}</pre>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Performance Tab -->
    <div class="fs-panel" id="fs-panel-performance">
        <p><strong>Response Time:</strong> {{ $performance['response_time'] }} s</p>
        <p><strong>Total DB Time:</strong> {{ $queries['total_time'] }} s</p>
        <p><strong>Query Count:</strong> {{ $queries['count'] }}</p>
        <p><strong>Memory Usage:</strong> {{ number_format(memory_get_usage() / 1024 / 1024, 2) }} MB</p>
        <p><strong>Peak Memory:</strong> {{ number_format(memory_get_peak_usage() / 1024 / 1024, 2) }} MB</p>
        <p><strong>PHP:</strong> {{ phpversion() }}</p>
        <p><strong>Laravel:</strong> {{ app()->version() }}</p>
    </div>

    <!-- Request Tab -->
    <div class="fs-panel" id="fs-panel-request">
        <p><strong>Method:</strong> {{ request()->method() }}</p>
        <p><strong>URL:</strong> {{ request()->fullUrl() }}</p>
        <p><strong>Query Params:</strong> {{ json_encode(request()->query(), JSON_PRETTY_PRINT) }}</p>
        <p><strong>Headers:</strong> {{ json_encode(getallheaders(), JSON_PRETTY_PRINT) }}</p>
        <p><strong>Cookies:</strong> {{ json_encode(request()->cookie(), JSON_PRETTY_PRINT) }}</p>
    </div>

    <!-- Views Tab -->
    <div class="fs-panel" id="fs-panel-views">
        @foreach ($view as $index => $viewInfo)
            @php
                $viewData = $viewInfo['data'] ?? [];
                $viewFilePath = isset($viewInfo['path'])
                    ? \Illuminate\Support\Str::after($viewInfo['path'], base_path() . DIRECTORY_SEPARATOR)
                    : 'N/A';
            @endphp

            <!-- Collapsible header -->
            <div class="fs-view-header">
                <span>View #{{ $index + 1 }}: {{ $viewFilePath }}</span>
                <span class="fs-view-toggle-icon">&#9654;</span>
            </div>

            <!-- Collapsible content -->
            <div class="fs-view-data">
                <pre>{{ json_encode($viewData, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endforeach
    </div>

    <!-- Logs Tab -->
    <div class="fs-panel" id="fs-panel-logs">
        @foreach ($logs ?? [] as $log)
            <p>[{{ $log['level'] ?? 'info' }}] {{ $log['message'] }}</p>
        @endforeach
    </div>
</div>

<script src="{{ asset('vendor/flowsense/js/flowsense-toolbar.js') }}"></script>
