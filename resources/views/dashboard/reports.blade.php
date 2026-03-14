<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} | Reports</title>
    <style>
        :root {
            --bg: #f6f7fb;
            --panel: #ffffff;
            --line: #eceff3;
            --text: #111827;
            --muted: #6b7280;
            --brand: #b2070f;
            --brand-soft: #fff3f4;
            --active: #fff2f2;
            --purple: #4f46e5;
            --shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 230px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            background: var(--panel);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .side-top {
            padding: 22px 18px;
        }

        .brand {
            font-size: 31px;
            margin: 0 0 18px;
            color: var(--brand);
            font-weight: 700;
            letter-spacing: -0.4px;
        }

        .nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav a {
            display: block;
            text-decoration: none;
            color: #334155;
            padding: 12px 10px;
            border-radius: 10px;
            font-weight: 500;
        }

        .nav a.active {
            background: var(--active);
            color: #8f1017;
        }

        .side-bottom {
            padding: 0 16px 16px;
            border-top: 1px solid var(--line);
        }

        .profile-banner {
            margin: 14px -16px 10px;
            background: var(--purple);
            color: #fff;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 13px;
        }

        .profile-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #b91c1c;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            flex: 0 0 36px;
        }

        .profile-meta {
            min-width: 0;
        }

        .profile-name {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-email {
            margin: 2px 0 0;
            font-size: 12px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout {
            display: block;
            margin-top: 10px;
            color: #334155;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 2px;
        }

        .content {
            padding: 24px;
        }

        .title {
            margin: 0;
            font-size: 40px;
            font-weight: 700;
            letter-spacing: -0.4px;
        }

        .subtitle {
            margin: 4px 0 20px;
            color: var(--muted);
            font-size: 15px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1050px;
        }

        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow);
            padding: 20px;
        }

        .card h3 {
            margin: 0 0 6px;
            font-size: 18px;
        }

        .card p {
            margin: 0 0 14px;
            color: var(--muted);
            font-size: 14px;
        }

        .card a {
            color: #a41414;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 1100px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 840px) {
            .dashboard {
                grid-template-columns: 1fr;
            }

            .sidebar {
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }
        }
    </style>
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="side-top">
            <h1 class="brand">UM EduFlow</h1>
            <ul class="nav">
                <li><a href="#">Overview</a></li>
                <li><a href="#">Students</a></li>
                <li><a href="#">Programs</a></li>
                <li><a href="#">Subjects</a></li>
                <li><a href="#">Enrollment</a></li>
                <li><a class="active" href="{{ url('/dashboard/reports') }}">Reports</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </div>

        <div class="side-bottom">
            <div class="profile-banner">{{ $dashboardIdentity['school_year'] }}</div>

            <div class="profile-card">
                <div class="avatar">{{ $dashboardIdentity['initials'] }}</div>
                <div class="profile-meta">
                    <p class="profile-name">{{ $dashboardIdentity['name'] }}</p>
                    <p class="profile-email">{{ $dashboardIdentity['email'] }}</p>
                </div>
            </div>

            <a class="logout" href="#">Logout</a>
        </div>
    </aside>

    <main class="content">
        <h2 class="title">Reports</h2>
        <p class="subtitle">Generate and download enrollment reports</p>

        <section class="cards">
            <article class="card">
                <h3>Enrollment Summary</h3>
                <p>Overview of enrollments by course and period</p>
                <a href="#">Generate</a>
            </article>

            <article class="card">
                <h3>Student Roster</h3>
                <p>List of enrolled students by course</p>
                <a href="#">Generate</a>
            </article>

            <article class="card">
                <h3>Academic Calendar</h3>
                <p>Important dates and deadlines</p>
                <a href="#">Generate</a>
            </article>
        </section>
    </main>
</div>
</body>
</html>
