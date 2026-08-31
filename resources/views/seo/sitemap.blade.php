<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($staticPaths as $path)
        <url><loc>{{ $baseUrl }}{{ $path }}</loc></url>
    @endforeach
    @foreach($tours as $tour)
        <url>
            <loc>{{ $baseUrl }}/tours/{{ $tour->slug }}</loc>
            <lastmod>{{ $tour->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>
