@php
    $manifestPath = public_path('build/manifest.json');

    if (! is_file($manifestPath)) {
        return;
    }

    $manifest = json_decode((string) file_get_contents($manifestPath), true);

    if (! is_array($manifest)) {
        return;
    }
    $entry = $manifest['resources/js/admin.js'] ?? null;

    if (! is_array($entry)) {
        return;
    }

    $files = [];

    if (isset($entry['file'])) {
        $files[] = $entry['file'];
    }

    foreach ($entry['imports'] ?? [] as $importKey) {
        $import = $manifest[$importKey] ?? null;

        if (is_array($import) && isset($import['file'])) {
            $files[] = $import['file'];
        }
    }

    $dashboard = $manifest['resources/js/Pages/Admin/Dashboard.vue'] ?? null;

    if (is_array($dashboard) && isset($dashboard['file'])) {
        $files[] = $dashboard['file'];

        foreach ($dashboard['imports'] ?? [] as $importKey) {
            $import = $manifest[$importKey] ?? null;

            if (is_array($import) && isset($import['file'])) {
                $files[] = $import['file'];
            }
        }
    }

    $files = array_values(array_unique($files));
@endphp

@foreach ($files as $file)
    <link rel="modulepreload" href="{{ asset('build/'.$file) }}" as="script" crossorigin>
@endforeach
