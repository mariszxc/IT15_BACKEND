<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} | Posts</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600" rel="stylesheet" />
        <style>
            :root {
                color-scheme: light;
                --ink: #1f1a15;
                --muted: #6d625b;
                --paper: #f6f2ea;
                --card: #fffefb;
                --accent: #d0693c;
                --accent-soft: #ffe7da;
                --line: #e7ded4;
                --shadow: 0 12px 28px rgba(22, 16, 12, 0.08);
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
                background: radial-gradient(circle at top, #fdfaf4, var(--paper));
                color: var(--ink);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                padding: 32px 18px 48px;
            }

            .layout {
                width: min(1100px, 100%);
                display: grid;
                grid-template-columns: 220px 1fr;
                gap: 24px;
            }

            .panel {
                background: var(--card);
                border: 1px solid var(--line);
                border-radius: 16px;
                padding: 22px;
                box-shadow: var(--shadow);
            }

            .title {
                font-size: 24px;
                font-weight: 600;
                margin: 0 0 10px;
            }

            .subtitle {
                font-size: 14px;
                color: var(--muted);
                margin: 0 0 20px;
            }

            .category-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .category-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 12px;
                border-radius: 12px;
                border: 1px solid transparent;
                color: inherit;
                text-decoration: none;
                background: #fff;
                transition: transform 0.15s ease, border-color 0.15s ease;
            }

            .category-link:hover {
                transform: translateX(4px);
                border-color: var(--line);
            }

            .category-link.is-active {
                border-color: var(--accent);
                background: var(--accent-soft);
                font-weight: 600;
            }

            .cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 18px;
            }

            .card {
                background: var(--card);
                border-radius: 14px;
                padding: 18px;
                border: 1px solid var(--line);
                box-shadow: 0 10px 20px rgba(22, 16, 12, 0.05);
            }

            .card h3 {
                margin: 0 0 8px;
                font-size: 18px;
                font-weight: 600;
            }

            .card p {
                margin: 0;
                font-size: 14px;
                color: var(--muted);
                line-height: 1.5;
            }

            .empty {
                padding: 20px;
                border-radius: 12px;
                border: 1px dashed var(--line);
                color: var(--muted);
                background: #fff;
            }

            @media (max-width: 840px) {
                .layout {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        @php
            $activeSlug = $activeCategory?->slug;
        @endphp
        <div class="layout">
            <aside class="panel">
                <h1 class="title">Categories</h1>
                <p class="subtitle">Pick a topic to filter the posts.</p>
                <ul class="category-list">
                    @foreach ($categories as $category)
                        <li>
                            <a
                                class="category-link {{ $activeSlug === $category->slug ? 'is-active' : '' }}"
                                href="{{ url('/?category=' . $category->slug) }}"
                            >
                                <span>{{ $category->name }}</span>
                                <span>-></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <section class="panel">
                <h2 class="title">{{ $activeCategory?->name ?? 'All Posts' }}</h2>
                <p class="subtitle">Simple cards with title and description.</p>

                @if ($posts->isEmpty())
                    <div class="empty">No posts found for this category.</div>
                @else
                    <div class="cards">
                        @foreach ($posts as $post)
                            <article class="card">
                                <h3>{{ $post->title }}</h3>
                                <p>{{ $post->description }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </body>
</html>
